<?php

declare(strict_types=1);

namespace ISAAC\ComposerGitHooks;

interface LoggerInterface
{
    public function writeInfo(string $message): void;

    public function writeError(string $message): void;
}
