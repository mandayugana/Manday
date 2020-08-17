<?php

namespace Manday\Log\Logger;

use Manday\Log\Logger\AbstractLogger;

class NullLogger extends AbstractLogger
{
    /**
     * {@inheritdoc}
     */
    protected function writeLog(string $tag, string $message, array $context = [], $loggerLevel): void
    {
        // do nothing
    }
}
