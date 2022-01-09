<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class FileLoggerNotificationChannel implements NotificationChannelInterface
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function send(string $recipient, string $message): bool
    {
        $this->logger->info($message);

        return true;
    }
}
