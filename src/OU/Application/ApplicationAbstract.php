<?php

namespace OU\Application;

use OU\DI;
use OU\ErrorCatcher;

abstract class ApplicationAbstract
{
    /**
     * @var DI
     */
    protected $di;

    /**
     * @var \stdClass
     */
    protected $config;

    /**
     * @var ErrorCatcher
     */
    protected $errorCatcher;

    /**
     * @param array $configs
     */
    public function __construct(array $configs = array())
    {
        $this->di = new DI();
        $this->errorCatcher = new ErrorCatcher();
        $this->errorCatcher->register();
        $this->config = json_decode(json_encode($configs));
        $this->di->setShared('config', $this->config);
        $this->di->setShared('app', $this);
    }

    /**
     * @param $callback
     */
    public function setExceptionHandler($callback)
    {
        $app = $this;
        $this->getErrorCatcher()->setExceptionHandler(function ($exception) use ($callback, $app) {
            $params = $app->getHandlerParams();
            array_push($params, $exception);
            call_user_func_array($callback, $params);
        });
    }

    /**
     * @param $callback
     */
    public function setFatalErrorHandler($callback)
    {
        $app = $this;
        $this->getErrorCatcher()->setFatalErrorHandler(function ($errorInfo) use ($callback, $app) {
            $params = $app->getHandlerParams();
            array_push($params, $errorInfo);
            call_user_func_array($callback, $params);
        });
    }

    /**
     * @return array
     */
    public function getHandlerParams()
    {
        return array($this);
    }

    /**
     * @return \stdClass
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return ErrorCatcher
     */
    public function getErrorCatcher()
    {
        return $this->errorCatcher;
    }

    /**
     * return DI
     */
    public function getDi()
    {
        return $this->di;
    }
}
