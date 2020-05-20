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

use function file_exists;
use function getcwd;
use function mkdir;
use function readlink;
use function sprintf;
use function symlink;

class GitHooks
{
    public const HOOKS = [
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
    public const GIT_HOOKS_DIRECTORY = '.git/hooks';
    public const PROJECT_HOOKS_DIRECTORY = 'bin/git-hooks';
    public const CHAIN_HOOK_FILENAME = 'scripts/chain-hook';

    /** @var LoggerInterface */
    private $logger;

    /** @var FileSystem */
    private $fileSystem;

    public function __construct(
        LoggerInterface $logger,
        FileSystem $fileSystem
    ) {
        $this->logger = $logger;
        $this->fileSystem = $fileSystem;
    }

    public function install(): void
    {
        if (!file_exists('.git')) {
            $this->logger->writeError('No .git directory found in ' . getcwd());
            return;
        }

        self::symlinkHooks();
        self::createProjectHookDirectories();

        $this->logger->writeInfo('Updated git-hooks');
    }

    private function symlinkHooks(): void
    {
        foreach (self::HOOKS as $hook) {
            $target = __DIR__ . '/../' . self::CHAIN_HOOK_FILENAME;
            $link = getcwd() . '/' . self::GIT_HOOKS_DIRECTORY . '/' . $hook;
            $relativeTarget = $this->fileSystem->getRelativePath($link, $target);

            if (!file_exists($link)) {
                symlink(
                    $relativeTarget,
                    $link
                );
                $this->logger->writeInfo('Created symlink ' . $link . ' -> ' . $relativeTarget);
            } elseif (!readlink($link) || readlink($link) !== $relativeTarget) {
                $this->logger->writeError(sprintf('Git hook %s already exists, not using project hooks.
                    Consider moving your custom hook to %s.', $link, self::PROJECT_HOOKS_DIRECTORY));
            }
        }
    }

    private function createProjectHookDirectories(): void
    {
        foreach (self::HOOKS as $hook) {
            $directory = getcwd() . '/' . self::PROJECT_HOOKS_DIRECTORY . '/' . $hook . '.d';
            if (!file_exists($directory)) {
                mkdir($directory);
                $this->logger->writeInfo('Created directory ' . $directory);
            }
        }
    }
}
