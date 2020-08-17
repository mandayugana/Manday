<?php

namespace Manday\Log\Logger;

use Manday\Log\Logger\AbstractLogger;
use Manday\Log\LogLevel;

class NullLogger extends AbstractLogger
{
    /**
     * {@inheritdoc}
     */
    public function log(string $tag, string $message, array $context = [], $level = LogLevel::INFO): void
    {
        // do nothing :)
    }
}
