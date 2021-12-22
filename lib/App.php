<?php

namespace Uccu\SwKoaServer;

use Uccu\SwKoaPlugin\PluginLoader;
use Psr\Log\LoggerInterface;

class App
{

    /**
     * @var Config
     */
    static public $config;

    /**
     * @var LoggerInterface
     */
    static public $logger;

    public function start()
    {
        $pluginLoader = new PluginLoader;
        $manager = new PoolManager;

        $pluginLoader->load();

        $pluginLoader->poolStartBefore($manager);
        $manager->start();
        $pluginLoader->poolStartAfter($manager);
    }
}
