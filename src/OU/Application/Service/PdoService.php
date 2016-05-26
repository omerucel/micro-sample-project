<?php

namespace OU\Application\Service;

use OU\DI;
use OU\PDO\Wrapper;
use OU\Service;

class PdoService implements Service
{
    /**
     * @param DI $di
     * @return Wrapper
     * @throws \Exception
     */
    public function getService(DI $di)
    {
        $config = $di->get('config');
        try {
            $pdo = new \PDO($config->pdo->dsn, $config->pdo->username, $config->pdo->password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $exception) {
            /**
             * @var \Monolog\Logger $logger
             */
            $logger = $di->get('logger_helper')->getLogger();
            $logger->critical($exception);
            throw $exception;
        }
        return new Wrapper($pdo);
    }
}
