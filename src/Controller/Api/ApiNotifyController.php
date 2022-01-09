<?php

namespace App\Controller\Api;

use App\Service\NotificationChannelFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiNotifyController
{
    #[Route('/api/notify/{recipient}/{message}/{channel}', name: 'notify', methods: ['POST'])]
    public function notify(
        NotificationChannelFactory $channelFactory,
        string $recipient,
        string $message,
        string $channel
    ): Response {
        $currentChannel = $channelFactory->create();

        if ($currentChannel) {
            $currentChannel->send($recipient, $message);
            $result = 'You have sent this message: ' . $message . PHP_EOL .
                      'Recipient: ' . $recipient . PHP_EOL .
                      'Used channel: ' . $channel;
        } else {
            $result = 'Failed to send message';
        }


        return new Response($result);
    }
}
