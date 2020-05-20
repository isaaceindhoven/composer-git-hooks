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

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

class ComposerPlugin implements PluginInterface, EventSubscriberInterface
{
    public function activate(Composer $composer, IOInterface $io): void
    {
        // noop
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => 'process',
            ScriptEvents::POST_UPDATE_CMD => 'process',
        ];
    }

    public function process(Event $event): void
    {
        $io = $event->getIO();

        $logger = new ComposerLogger($io);
        $fileSystem = new FileSystem($logger);
        $gitHooks = new GitHooks($logger, $fileSystem);
        $gitHooks->install();
    }
}
