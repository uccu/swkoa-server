<?php

namespace Uccu\SwKoaServer;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Swoole\Constant;
use Swoole\Process;
use Swoole\Process\Manager;
use Swoole\Process\Pool;

class PoolManager extends Manager implements LoggerAwareInterface
{

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function __construct(int $msgQueueKey = 0)
    {
        parent::__construct(SWOOLE_IPC_UNIXSOCK, $msgQueueKey);
    }

    public function start(): void
    {
        $this->pool = new Pool(count($this->startFuncMap), $this->ipcType, $this->msgQueueKey, true);

        $this->pool->on(Constant::EVENT_WORKER_START, function (Pool $pool, int $workerId) {
            Process::signal(SIGTERM, function () {
                if (!is_null($this->logger)) {
                    $this->logger->info("worker sigterm");
                } else {
                    echo "worker sigterm";
                }
            });
            [$func] = $this->startFuncMap[$workerId];
            $func($pool, $workerId);
        });

        $this->pool->on(Constant::EVENT_WORKER_STOP, function () {
            if (!is_null($this->logger)) {
                $this->logger->info("worker stop");
            } else {
                echo "worker stop";
            }
        });

        $this->pool->start();
    }
}
