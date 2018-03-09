<?php

namespace Manday\Log;

use Manday\Log\LoggerInterface;

/**
 * Describes a logger-aware instance
 */
interface LoggerAwareInterface
{
    /**
     * Sets a logger instance on the object.
     *
     * @param \Manday\Log\LoggerInterface $logger The logger.
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void;
}