<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class RequestListener
{
    /**
     * @throws UnsupportedMediaTypeHttpException
     */
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (str_contains($request->getRequestUri(), $request->getLocale().'/'.'api')) {
            if (!('application/json' === $request->headers->get('content-type'))) {
                throw new UnsupportedMediaTypeHttpException("Your request must contain a Content-type header with the value 'application/json'.");
            }
        }
    }
}
