<?php

namespace Manday\Log\Logger;

use Manday\Log\Logger\AbstractLogger;

class NullLogger extends AbstractLogger
{
    /**
     * {@inheritdoc}
     */
    public function log($level, string $message, array $context = []): void
    {
        // do nothing :)
    }
}
