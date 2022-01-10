<?php

namespace App\Service;

interface NotificationChannelInterface
{
    public function send(string $recipient, string $message);
}
