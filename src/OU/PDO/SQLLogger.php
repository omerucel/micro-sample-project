<?php

namespace OU\PDO;

use OU\DI;
use OU\MicroTimer;
use Psr\Log\LoggerInterface;

class SQLLogger
{
    /**
     * @var DI
     */
    protected $di;

    /**
     * @var MicroTimer
     */
    protected $timer;

    /**
     * @var bool
     */
    protected $status = true;

    /**
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
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
        return $this->di->get('logger_helper')->getLogger();
    }

    public function enable()
    {
        $this->status = true;
    }

    public function disable()
    {
        $this->status = false;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->status;
    }
}
