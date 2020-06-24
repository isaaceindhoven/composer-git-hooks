<?php

/**
 * Do not remove or alter the notices in this preamble.
 * This software code regards ISAAC Standard Software.
 * Copyright © 2020 ISAAC and/or its affiliates.
 * www.isaac.nl All rights reserved. License grant and user rights and obligations
 * according to applicable license agreement. Please contact sales@isaac.nl for
 * questions regarding license and user rights.
 */

declare(strict_types=1);

namespace ISAAC\ComposerGitHooks;

use ISAAC\ComposerGitHooks\Exception\ProjectRootNotFoundException;

use function array_pad;
use function array_shift;
use function copy;
use function count;
use function dir;
use function dirname;
use function explode;
use function file_exists;
use function getcwd;
use function implode;
use function is_dir;
use function is_file;
use function is_link;
use function mkdir;
use function readlink;
use function rtrim;
use function sprintf;
use function str_replace;
use function symlink;
use function unlink;

class FileSystem
{
    private const DIR_PERMISSIONS = 0755;

    /** @var Logger */
    private $logger;

    public function __construct(
        Logger $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @throws ProjectRootNotFoundException
     */
    public function getProjectRoot(): string
    {
        $projectRoot = getcwd();
        if ($projectRoot === false) {
            throw new ProjectRootNotFoundException();
        }

        while (!file_exists(sprintf('%s/.git', $projectRoot))) {
            $newProjectRoot = dirname($projectRoot);
            if ($projectRoot === $newProjectRoot) {
                // We cannot go up further
                $projectRoot = null;
                break;
            }

            $projectRoot = $newProjectRoot;
        }

        if ($projectRoot === null) {
            throw new ProjectRootNotFoundException();
        }

        return $projectRoot;
    }

    // phpcs:ignore ObjectCalisthenics.Files.FunctionLength.ObjectCalisthenics\Sniffs\Files\FunctionLengthSniff
    public function getRelativePath(string $from, string $to): string
    {
        // some compatibility fixes for Windows paths
        $from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
        $to = is_dir($to)   ? rtrim($to, '\/') . '/'   : $to;
        $from = str_replace('\\', '/', $from);
        $to = str_replace('\\', '/', $to);

        $from = explode('/', $from);
        $to = explode('/', $to);
        $relPath = $to;

        foreach ($from as $depth => $dir) {
            // find first non-matching dir
            if ($dir === $to[$depth]) {
                // ignore this directory
                array_shift($relPath);
            } else {
                // get number of remaining dirs to $from
                $remaining = count($from) - $depth;
                if ($remaining > 1) {
                    // add traversals up to first matching dir
                    $padLength = (count($relPath) + $remaining - 1) * -1;
                    $relPath = array_pad($relPath, $padLength, '..');
                    break;
                } else {
                    $relPath[0] = './' . $relPath[0];
                }
            }
        }
        return implode('/', $relPath);
    }

    public function createDirectoryIfNotExists(string $directory): void
    {
        if (!file_exists($directory)) {
            if (!mkdir($directory, self::DIR_PERMISSIONS, true)) {
                $this->logger->writeError(sprintf('Failed to create %s', $directory));
                exit(1);
            }

            $this->logger->writeInfo(sprintf('Created directory %s', $directory));
        } elseif (!is_dir($directory)) {
            $this->logger->writeError(sprintf('%s exists but is not a directory', $directory));
        } else {
            $this->logger->writeInfo(sprintf('Not creating directory %s because it already exists', $directory));
        }
    }

    protected function performRecursive(string $source, string $dest, FileSystemAction $action): bool
    {
        if (is_link($source)) {
            return readlink($source) !== false ? symlink(readlink($source), $dest) : false;
        } elseif (is_file($source)) {
            return $action->invoke($source, $dest);
        } elseif (!is_dir($dest)) {
            mkdir($dest, self::DIR_PERMISSIONS);
        }

        $dir = dir($source);
        if (!$dir) {
            return false;
        }

        while (($entry = $dir->read()) !== false) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            self::performRecursive($source . '/' . $entry, $dest . '/' . $entry, $action);
        }
        $dir->close();
        return true;
    }

    public function copyRecursive(string $source, string $dest): bool
    {
        return $this->performRecursive($source, $dest, new class implements FileSystemAction {
            public function invoke(string $source, string $dest): bool
            {
                return copy($source, $dest);
            }
        });
    }

    public function symlinkRecursive(string $source, string $dest): bool
    {
        return $this->performRecursive($source, $dest, new class ($this) implements FileSystemAction {
            /** @var FileSystem */
            private $fileSystem;

            public function __construct(FileSystem $fileSystem)
            {
                $this->fileSystem = $fileSystem;
            }

            public function invoke(string $source, string $dest): bool
            {
                // We want to replace any existing files or symlinks from previous copyright installs by a symlink
                if (is_file($dest)) {
                    unlink($dest);
                }

                // Use relative paths in case we are working with docker
                $relativePath = $this->fileSystem->getRelativePath($dest, $source);
                return symlink($relativePath, $dest);
            }
        });
    }
}
