<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Notifier\Bridge\Telegram\TelegramOptions;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\Message\ChatMessage;

class TelegramNotificationChannel implements NotificationChannelInterface
{
    private $chatter;
    private $logger;

    public function __construct(ChatterInterface $chatter, LoggerInterface $logger)
    {
        $this->chatter = $chatter;
        $this->logger = $logger;
    }

    public function send(string $recipient, string $message): void
    {
        $chatMessage = new ChatMessage($message);

        $telegramOptions = (new TelegramOptions())
            ->chatId($recipient)
            ->parseMode('MarkdownV2');

        $chatMessage->options($telegramOptions);

        try {
            $this->chatter->send($chatMessage);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('Failed to send the message: ' . $e, ['recipient' => $recipient]);
            throw $e;
        }
    }
}
