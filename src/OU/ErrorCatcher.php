<?php

namespace OU;

class ErrorCatcher
{
    protected $fatalErrorHandler = null;
    protected $exceptionHandler = null;

    public function register()
    {
        set_error_handler(array($this, 'handleError'));
        set_exception_handler(array($this, 'handleException'));
        register_shutdown_function(array($this, 'handleFatalError'));
    }

    public function unregister()
    {
        restore_error_handler();
        restore_exception_handler();
    }

    /**
     * @param $callback
     */
    public function setFatalErrorHandler($callback)
    {
        $this->fatalErrorHandler = $callback;
    }

    /**
     * @param $callback
     */
    public function setExceptionHandler($callback)
    {
        $this->exceptionHandler = $callback;
    }

    /**
     * @param $exception
     */
    public function handleException($exception)
    {
        if ($this->exceptionHandler != null) {
            call_user_func_array($this->exceptionHandler, [$exception]);
        }
    }

    /**
     * @param $errNo
     * @param $errStr
     * @param $errFile
     * @param $errLine
     * @throws \ErrorException
     */
    public function handleError($errNo, $errStr, $errFile, $errLine)
    {
        throw new \ErrorException($errStr, $errNo, 0, $errFile, $errLine);
    }

    public function handleFatalError()
    {
        $errorInfo = error_get_last();
        if ($errorInfo !== null && $this->fatalErrorHandler != null) {
            call_user_func_array($this->fatalErrorHandler, [$errorInfo]);
        }
    }
}
