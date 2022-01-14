<?php

namespace App\SomeModule\Command;

use App\Command\AbstractCustomCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * OneMoreCommand is the custom command that can be chained.
 */
class OneMoreCommand extends AbstractCustomCommand
{
    /**
     * @inheritdoc
     */
    protected static $defaultName = 'some:command';

    /**
     * @inheritdoc
     */
    protected static $defaultDescription = 'One More Command';

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'This is one more command.';
        $output->writeln([$message, '']);
        $this->logger->info($message);

        return Command::SUCCESS;
    }
}
