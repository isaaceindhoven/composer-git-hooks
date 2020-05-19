<?php

declare(strict_types=1);

namespace ISAAC\ComposerGitHooks;

interface FileSystemActionInterface
{
    public function invoke(string $source, string $dest): bool;
}
