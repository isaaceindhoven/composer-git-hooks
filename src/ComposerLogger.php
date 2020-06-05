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

use Composer\IO\IOInterface;

use function sprintf;

class ComposerLogger implements Logger
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
        $this->io->writeError(sprintf('<error>isaac/composer-git-hooks (ERROR):</error> %s', $message));
    }
}
