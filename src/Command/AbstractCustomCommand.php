<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;

/**
 * AbstractCustomCommand is the abstract class extended by all custom commands that can be chained.
 */
abstract class AbstractCustomCommand extends Command
{
    protected LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger The current logger instance.
     */
    public function __construct(LoggerInterface $logger)
    {
        parent::__construct();
        $this->logger = $logger;
    }
}
