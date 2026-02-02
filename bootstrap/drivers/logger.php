<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

return function (): LoggerInterface {
    $logger = new Logger('app');

    $logger->pushHandler(new StreamHandler(__DIR__ . '/../../storage/logs/app.log', 100));

    return $logger;
};
