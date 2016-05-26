<?php

namespace OU\Application\Service;

use OU\DI;
use OU\Logger\MonologHelper;
use OU\Service;

class LoggerHelperService implements Service
{
    /**
     * @param DI $di
     * @return MonologHelper
     */
    public function getService(DI $di)
    {
        return new MonologHelper($di);
    }
}
