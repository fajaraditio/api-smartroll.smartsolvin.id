<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

if (!function_exists('logger')) {
    function logger($level, string $message, array $context = [])
    {
        static $logger = null;

        if ($logger === null) {
            $logger = new Logger('app');
            $logger->pushHandler(new StreamHandler(__DIR__ . '/../storage/logs/app.log', 100));
        }

        $logger->log($level, $message, $context);
    }
}
