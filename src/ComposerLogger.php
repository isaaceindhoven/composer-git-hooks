<?php

declare(strict_types=1);

namespace ISAAC\ComposerGitHooks;

use Composer\IO\IOInterface;

use function sprintf;

class ComposerLogger implements LoggerInterface
{
    /** @var IOInterface */
    private $io;

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
        $this->io->writeError(sprintf('<info>isaac/composer-git-hooks (ERROR):</info> %s', $message));
    }
}
