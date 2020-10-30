<?php

declare(strict_types=1);

namespace ISAAC\ComposerGitHooks;

interface FileSystemAction
{
    public function invoke(string $source, string $dest): bool;
}
