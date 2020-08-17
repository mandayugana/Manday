<?php

namespace Manday\Log\Logger;

use Manday\Log\Logger\AbstractLogger;
use Manday\Log\LogLevel;

class SyslogLogger extends AbstractLogger
{
    /**
     * {@inheritdoc}
     */
    protected function writeLog(string $tag, string $message, array $context = [], $loggerLevel): void
    {
        openlog($tag, \LOG_NDELAY | \LOG_PID, \LOG_USER);
        syslog($loggerLevel, $message);
        closelog();
    }

    /**
     * {@inheritdoc}
     */
    protected function mapLevels(): array
    {
        return [
            LogLevel::EMERGENCY => \LOG_EMERG,
            LogLevel::ALERT => \LOG_ALERT,
            LogLevel::CRITICAL => \LOG_CRIT,
            LogLevel::ERROR => \LOG_ERR,
            LogLevel::WARNING => \LOG_WARNING,
            LogLevel::NOTICE => \LOG_NOTICE,
            LogLevel::INFO => \LOG_INFO,
            LogLevel::DEBUG => \LOG_DEBUG,
        ];
    }
}
