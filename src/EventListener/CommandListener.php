<?php

namespace App\EventListener;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use App\Service\Command\CommandCollection;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Command\Command;

class CommandListener
{
    private iterable $rootCommand;
    private iterable $chainMembers;
    private ContainerInterface $commandCollection;
    private LoggerInterface $logger;

    /**
     * @param iterable          $rootCommand       An iterable containing the master command.
     * @param iterable          $chainMembers      An iterable containing the command chain members.
     * @param CommandCollection $commandCollection Service locator with all services tagged with a specific tag.
     * @param LoggerInterface   $logger            The current logger instance.
     */
    public function __construct(
        #[TaggedIterator('app.master_command')] iterable $rootCommand,
        #[TaggedIterator('app.command_chain')] iterable $chainMembers,
        CommandCollection $commandCollection,
        LoggerInterface $logger
    ) {
        $this->rootCommand = $rootCommand instanceof \Traversable ? iterator_to_array($rootCommand) : $rootCommand;
        $this->chainMembers = $chainMembers instanceof \Traversable ? iterator_to_array($chainMembers) : $chainMembers;
        $this->commandCollection = $commandCollection->getCommandCollection();
        $this->logger = $logger;
    }

    /**
     * Executes before the command is run.
     *
     * @param ConsoleCommandEvent $event
     *
     * @throws \ErrorException
     */
    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $command = $event->getCommand();
        $commandName = $command->getName();
        $rootCommandName = $this->getRootCommandName();

        $this->validateRootCommand($command);

        if ($this->commandCollection->has($command::class)) {
            $event->disableCommand();
            throw new \ErrorException($commandName . ' command is a member of foo:hello command chain and cannot be executed on its own.', 1);
        }

        if ($commandName === $rootCommandName && $this->chainMembers) {
            $this->logger->debug($rootCommandName . ' is a master command of a command chain that has registered member commands');
            foreach ($this->chainMembers as $member) {
                $this->logger->debug($member->getName() . ' is registered as a member of ' . $rootCommandName . ' command chain');
            }
            $this->logger->debug('Executing ' . $rootCommandName . ' command itself first:');
        }
    }

    /**
     * Executes after the command has been executed.
     *
     * @param ConsoleTerminateEvent $event
     *
     * @throws \ErrorException
     */
    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        $commandName = $event->getCommand()->getName();
        $rootCommandName = $this->getRootCommandName();
        $output = $event->getOutput();

        // execute chain members
        if ($commandName === $rootCommandName && $this->chainMembers) {
            $this->logger->debug('Executing ' . $rootCommandName . ' chain members:');
            foreach ($this->chainMembers as $member) {
                if ($member->run(new ArrayInput([]), $output) !== 0) {
                    throw new \ErrorException('Failed to execute command ' . $commandName);
                }
            }
            $this->logger->debug('Execution of ' . $rootCommandName . ' chain completed.');
        }
    }

    /**
     * Checks if the master command is defined correctly.
     *
     * @param Command $command Current Command instance.
     *
     * @throws \ErrorException
     */
    private function validateRootCommand(Command $command): void
    {
        if (!$this->rootCommand) {
            throw new \ErrorException('A root command must be specified.', 1);
        }

        if (count($this->rootCommand) > 1) {
            $rootCommands = '';
            foreach ($this->rootCommand as $command) {
                $rootCommands .= $command->getName() . '; ';
            }
            $rootCommands= substr_replace($rootCommands, '.', -2);

            throw new \ErrorException('There can be only one master command. ' . count($this->rootCommand) . ' commands are tagged as master: ' . $rootCommands, 1);
        }

        $rootCommandClassName = $this->rootCommand[0]::class;
        $commandClassName = $command::class;
        if ($this->commandCollection->has($commandClassName) && $rootCommandClassName === $commandClassName) {
            throw new \ErrorException('The master command ' . $rootCommandClassName . ' cannot be tagged as a chain member.', 1);
        }
    }

    /**
     * Gets master command name.
     *
     * @return string
     */
    private function getRootCommandName(): string
    {
        return $this->rootCommand[0]->getName();
    }
}
