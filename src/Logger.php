<?php

namespace App;

use Psr\Log\LoggerInterface;

class Logger
{
    /**
     * @var LoggerInterface
     */
    private static $logger;

    /**
     * @param LoggerInterface $logger
     */
    public static function init(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public static function getLogger()
    {
        return self::$logger;
    }
}