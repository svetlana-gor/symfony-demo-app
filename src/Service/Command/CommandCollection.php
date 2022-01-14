<?php

namespace App\Service\Command;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;

/**
 * CommandCollection service contains a service locator with all services
 * tagged with a specific tag.
 */
class CommandCollection
{
    private $commandCollection;

    /**
     * @param ContainerInterface $handlers
     *   Service locator with all services tagged with a specific tag.
     */
    public function __construct(
        #[TaggedLocator('app.command_chain')] ContainerInterface $handlers
    ) {
        $this->commandCollection = $handlers;
    }

    /**
     * @return ContainerInterface
     *   Service locator with all services tagged with a specific tag.
     */
    public function getCommandCollection()
    {
        return $this->commandCollection;
    }
}
