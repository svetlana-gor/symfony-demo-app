<?php

namespace App\Command;

use App\Service\Command\CommandCollection;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * AbstractCustomCommand is the abstract class extended by all custom commands that can be chained.
 */
abstract class AbstractCustomCommand extends Command
{
    protected $commandCollection;
    protected $logger;

    /**
     * @param CommandCollection $commandCollection Service locator with all services tagged with a specific tag.
     * @param LoggerInterface   $logger            The current logger instance.
     */
    public function __construct(CommandCollection $commandCollection, LoggerInterface $logger)
    {
        parent::__construct();

        $this->commandCollection = $commandCollection->getCommandCollection();
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     *
     * @throws \ErrorException Is throw when the current command is a member of root command and cannot be executed on its own.
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        if ($this->commandCollection) {
            if (!FooHelloCommand::$isFooHelloCommandCalled && $this->commandCollection->has(get_class($this))) {
                throw new \ErrorException(
                    'Error: ' .
                    $this->getName() .
                    ' command is a member of foo:hello command chain and cannot be executed on its own.'
                );
            }
        }
    }
}
