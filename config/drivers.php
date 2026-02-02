<?php

use Psr\Log\LoggerInterface;

return [
    PDO::class => (require __DIR__ . '/../bootstrap/drivers/pdo.php')(),
    LoggerInterface::class => (require __DIR__ . '/../bootstrap/drivers/logger.php')(),
];
