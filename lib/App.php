<?php

namespace Uccu\SwKoaServer;

use Uccu\SwKoaPlugin\PluginLoader;

class App
{

    /**
     * @var Config
     */
    static public $config;

    /**
     * @var \Uccu\SwKoaLog\Logger
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
