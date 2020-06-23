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
