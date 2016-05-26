<?php

namespace OU\PDO;

use OU\MicroTimer;
use Psr\Log\LoggerInterface;

class SQLLogger
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var MicroTimer
     */
    protected $timer;

    /**
     * @param LoggerInterface $logger
     */
    protected function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param $sql
     * @param $params
     */
    public function start($sql, array $params)
    {
        $this->timer = new MicroTimer();
    }

    /**
     * @param $sql
     * @param array $params
     */
    public function end($sql, array $params)
    {
        $this->getLogger()->info($sql . ' executed in ' . $this->timer . ' seconds.', $params);
    }

    /**
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        return $this->logger;
    }
}
