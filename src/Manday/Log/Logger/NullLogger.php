<?php

namespace Manday\Log\Logger;

use Manday\Log\Logger\AbstractLogger;

class NullLogger extends AbstractLogger
{
    /**
     * {@inheritdoc}
     */
    public function log(string $tag, string $message, array $context = [], $level): void
    {
        // do nothing :)
    }
}
