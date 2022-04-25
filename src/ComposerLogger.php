<?php

declare(strict_types=1);

namespace ISAAC\ComposerGitHooks;

use Composer\IO\IOInterface;

use function sprintf;

class ComposerLogger implements Logger
{
    private IOInterface $io;

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    public function writeInfo(string $message): void
    {
        $this->io->write(sprintf('<info>isaac/composer-git-hooks:</info> %s', $message));
    }

    public function writeError(string $message): void
    {
        $this->io->writeError(sprintf('<error>isaac/composer-git-hooks (error):</error> %s', $message));
    }

    public function writeWarning(string $message): void
    {
        $this->io->writeError(sprintf('<warning>isaac/composer-git-hooks (warning):</warning> %s', $message));
    }
}
