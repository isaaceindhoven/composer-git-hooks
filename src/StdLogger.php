<?php

declare(strict_types=1);

namespace ISAAC\ComposerGitHooks;

use function fwrite;
use function sprintf;

use const STDERR;
use const STDOUT;

class StdLogger implements Logger
{
    public function writeInfo(string $message): void
    {
        fwrite(STDOUT, sprintf("isaac/composer-git-hooks: %s\n", $message));
    }

    public function writeError(string $message): void
    {
        fwrite(STDERR, sprintf("isaac/composer-git-hooks (error): %s\n", $message));
    }

    public function writeWarning(string $message): void
    {
        fwrite(STDERR, sprintf("isaac/composer-git-hooks (warning): %s\n", $message));
    }
}
