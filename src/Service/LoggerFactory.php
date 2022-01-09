<?php

namespace App\Service;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class LoggerFactory
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

    public function create(): LoggerInterface
    {
        $request = $this->requestStack->getCurrentRequest();
        $currentLogger = $request->get('recipient');

        if (!$this->locator->has($currentLogger)) {
            return $this->logger;
        }

        return $this->locator->get($currentLogger);
    }
}
