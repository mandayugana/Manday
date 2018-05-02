<?php

namespace Manday\Log\Logger;

use Manday\Log\Logger\AbstractLogger;
use Manday\Log\LogLevel;
use Manday\Log\Exception\InvalidArgumentException;

class SyslogLogger extends AbstractLogger
{
    /**
     * Maps logger's log level to PHP-syslog's severity.
     * 
     * @var array
     */
    protected $map = [
        LogLevel::EMERGENCY => \LOG_EMERG,
        LogLevel::ALERT => \LOG_ALERT,
        LogLevel::CRITICAL => \LOG_CRIT,
        LogLevel::ERROR => \LOG_ERR,
        LogLevel::WARNING => \LOG_WARNING,
        LogLevel::NOTICE => \LOG_NOTICE,
        LogLevel::INFO => \LOG_INFO,
        LogLevel::DEBUG => \LOG_DEBUG,
    ];
    
    /**
     * {@inheritdoc}
     * 
     * @throws \Manday\Log\Exception\InvalidArgumentException If log level is
     * invalid.
     */
    public function log($level, string $message, array $context = []): void
    {
        if (!isset($this->map[$level])) {
            throw new InvalidArgumentException($level);
        }
        
        syslog($this->map[$level], $this->interpolate($message, $context));
    }
}
