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

use function file_exists;
use function readlink;
use function sprintf;
use function symlink;

class GitHooks
{
    private const HOOKS = [
        'applypatch-msg',
        'commit-msg',
        'fsmonitor-watchman',
        'p4-pre-submit',
        'post-applypatch',
        'post-checkout',
        'post-commit',
        'post-index-change',
        'post-merge',
        'post-rewrite',
        'pre-applypatch',
        'pre-auto-gc',
        'pre-commit',
        'pre-merge-commit',
        'pre-push',
        'pre-rebase',
        'prepare-commit-msg',
        'sendemail-validate',
    ];
    private const GIT_HOOKS_DIRECTORY = '.git/hooks';
    private const PROJECT_HOOKS_DIRECTORY = 'bin/git-hooks';
    private const PROJECT_DEFAULT_HOOK_DIRECTORIES = [
        'pre-commit',
    ];
    private const CHAIN_HOOK_FILENAME = 'scripts/chain-hook';

    /** @var Logger */
    private $logger;

    /** @var FileSystem */
    private $fileSystem;

    /** @var string */
    private $projectRoot;

    public function __construct(
        Logger $logger,
        FileSystem $fileSystem
    ) {
        $this->logger = $logger;
        $this->fileSystem = $fileSystem;

        try {
            $this->projectRoot = $this->fileSystem->getProjectRoot();
        } catch (ProjectRootNotFoundException $e) {
            $this->logger->writeError('No project root found');
            exit(1);
        }
    }

    public function install(): void
    {
        if (!file_exists(sprintf('%s/.git', $this->projectRoot))) {
            $this->logger->writeError(sprintf('No .git directory found in %s', $this->projectRoot));
            return;
        }

        $this->symlinkHooks();
        $this->createProjectHookDirectories();

        $this->logger->writeInfo('Updated git-hooks');
    }

    private function symlinkHooks(): void
    {
        foreach (self::HOOKS as $hook) {
            $target = sprintf('%s/../%s', __DIR__, self::CHAIN_HOOK_FILENAME);
            $link = sprintf('%s/%s/%s', $this->projectRoot, self::GIT_HOOKS_DIRECTORY, $hook);
            $relativeTarget = $this->fileSystem->getRelativePath($link, $target);

            if (!file_exists($link)) {
                symlink(
                    $relativeTarget,
                    $link
                );
                $this->logger->writeInfo(sprintf('Created symlink %s -> %s', $link, $relativeTarget));
            } elseif (!readlink($link) || readlink($link) !== $relativeTarget) {
                $this->logger->writeError(sprintf('Git hook %s already exists, not using project hooks.
                    Consider moving your custom hook to %s.', $link, self::PROJECT_HOOKS_DIRECTORY));
            }
        }
    }

    private function createProjectHookDirectories(): void
    {
        foreach (self::PROJECT_DEFAULT_HOOK_DIRECTORIES as $hook) {
            $directory = sprintf('%s/%s/%s.d', $this->projectRoot, self::PROJECT_HOOKS_DIRECTORY, $hook);
            $this->fileSystem->createDirectoryIfNotExists($directory);
        }
    }
}
