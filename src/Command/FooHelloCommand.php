<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

/**
 * FooHelloCommand can be executed by itself, and can accept and execute
 * a chain of other commands, if any.
 * Identifies the commands that belong to the chain using their tags.
 */
class FooHelloCommand extends Command
{

    /**
     * @var bool Indicates whether the FooHelloCommand has been called.
     */
    public static $isFooHelloCommandCalled = false;

    /**
     * @inheritdoc
     */
    protected static $defaultName = 'foo:hello';

    /**
     * @inheritdoc
     */
    protected static $defaultDescription = 'Foo Hello Command';

    protected $commands;
    protected $logger;

    /**
     * @param iterable        $commands
     *   All the services tagged with a specific tag.
     * @param LoggerInterface $logger
     *   The current logger instance.
     */
    public function __construct(
        #[TaggedIterator('app.command_chain')] iterable $commands,
        LoggerInterface $logger
    ) {
        parent::__construct();

        self::$isFooHelloCommandCalled = true;
        $this->commands = $commands instanceof \Traversable ? iterator_to_array($commands) : $commands;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->commands) {
            $this->logger->info($this->getName() . ' is a master command of a command chain that has registered member commands');
            foreach ($this->commands as $command) {
                $this->logger->info($command->getName() . ' is registered as a member of ' . $this->getName() . ' command chain');
            }
            $this->logger->info('Executing ' . $this->getName() . ' command itself first:');
        }

        $message = 'Hello from Foo!';
        $output->writeln([$message, '']);
        $this->logger->info($message);

        if ($this->commands) {
            $this->logger->info('Executing ' . $this->getName() . ' chain members:');
            foreach ($this->commands as $command) {
                $command->run(new ArrayInput([]), $output);
            }
            $this->logger->info('Execution of ' . $this->getName() . ' chain completed.');
        }

        return Command::SUCCESS;
    }
}
