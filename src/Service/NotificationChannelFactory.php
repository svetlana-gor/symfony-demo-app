<?php

namespace App\Service;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class NotificationChannelFactory implements ServiceSubscriberInterface
{
    private $requestStack;
    private $locator;
    private $logger;

    public function __construct(
        RequestStack $requestStack,
        ContainerInterface $locator,
        LoggerInterface $logger
    ) {
        $this->requestStack = $requestStack;
        $this->locator = $locator;
        $this->logger = $logger;
    }

    public static function getSubscribedServices(): array
    {
        return [
            'EmailNotificationChannel' => EmailNotificationChannel::class,
            'FileLoggerNotificationChannel' => FileLoggerNotificationChannel::class,
            'TelegramNotificationChannel' => TelegramNotificationChannel::class,
        ];
    }

    public function create(): ?NotificationChannelInterface
    {
        $request = $this->requestStack->getCurrentRequest();
        $currentChannel = $request->get('channel');

        if (!$this->locator->has($currentChannel)) {
            $this->logger->error("The channel $currentChannel does not exist.");
            return null;
        }

        return $this->locator->get($currentChannel);
    }
}
