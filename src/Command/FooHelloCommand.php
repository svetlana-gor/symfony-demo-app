<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * FooHelloCommand can be executed by itself, and can accept and execute a chain of other commands, if any.
 * Identifies the commands that belong to the chain using their tags.
 */
class FooHelloCommand extends AbstractCustomCommand
{
    /**
     * @inheritdoc
     */
    protected static $defaultName = 'foo:hello';

    /**
     * @inheritdoc
     */
    protected static $defaultDescription = 'Foo Hello Command';

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'Hello from Foo!';
        $output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
        $this->logger->info($message);

        return Command::SUCCESS;
    }
}
