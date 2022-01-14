<?php

namespace App\SomeOtherModule\Command;

use App\Command\AbstractCustomCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * BarHiCommand is the custom command that can be chained.
 */
class BarHiCommand extends AbstractCustomCommand
{
    /**
     * @inheritdoc
     */
    protected static $defaultName = 'bar:hi';

    /**
     * @inheritdoc
     */
    protected static $defaultDescription = 'Bar Hi Command';

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'Hi from Bar!';
        $output->writeln([$message, '']);
        $this->logger->info($message);

        return Command::SUCCESS;
    }
}
