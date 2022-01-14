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
     * @param ContainerInterface $commands
     *   Service locator with all services tagged with a specific tag.
     */
    public function __construct(
        #[TaggedLocator('app.command_chain')] ContainerInterface $commands
    ) {
        $this->commandCollection = $commands;
    }

    /**
     * @return ContainerInterface
     *   Service locator with all services tagged with a specific tag.
     */
    public function getCommandCollection(): ContainerInterface
    {
        return $this->commandCollection;
    }
}
