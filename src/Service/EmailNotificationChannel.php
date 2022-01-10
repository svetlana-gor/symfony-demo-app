<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailNotificationChannel implements NotificationChannelInterface
{
    private $mailer;
    private $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function send(string $recipient, string $message): void
    {
        $email = (new Email())
            ->from('admin.email@gmail.com')
            ->to($recipient)
            ->subject('Notification from Symfony Demo')
            ->text($message);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('Failed to send email: ' . $e, ['recipient' => $recipient]);
            throw $e;
        }
    }
}
