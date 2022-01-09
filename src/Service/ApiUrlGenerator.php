<?php

namespace App\Service;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;

class ApiUrlGenerator implements RouterInterface
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        return urldecode($this->router->generate($name, $parameters, $referenceType));
    }

    public function setContext(RequestContext $context)
    {
        $this->router->setContext($context);
    }

    public function getContext()
    {
        return $this->router->getContext();
    }

    public function getRouteCollection()
    {
        return $this->router->getRouteCollection();
    }

    public function match($pathinfo)
    {
        return $this->router->match($pathinfo);
    }
}
