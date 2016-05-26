<?php

namespace OU;

/**
 * Zaman ölçmek için kullanılan bir yardımcı sınıf.
 *
 * example:
 * <code>
 * $timer = new MicroTimer();
 * ...
 * ...
 * $this->getDefaultLogger->debug(__METHOD__ . ' duration : ' . $timer);
 * </code>
 */
class MicroTimer
{
    /**
     * @var mixed
     */
    protected $startTime;

    /**
     *
     */
    public function __construct()
    {
        $this->startTime = microtime(true);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return number_format(microtime(true) - $this->startTime, 4);
    }
}
