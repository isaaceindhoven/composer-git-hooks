<?php

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
