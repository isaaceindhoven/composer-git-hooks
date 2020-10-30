<?php

declare(strict_types=1);

namespace ISAAC\ComposerGitHooks;

interface Logger
{
    public function writeInfo(string $message): void;

    public function writeError(string $message): void;

    public function writeWarning(string $message): void;
}
