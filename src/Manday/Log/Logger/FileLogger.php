<?php

namespace Manday\Log\Logger;

use Manday\Log\Logger\AbstractLogger;
use Manday\Log\LogLevel;
use RuntimeException;

class FileLogger extends AbstractLogger
{
    protected $filename;

    public function __construct(string $filename)
    {
        // check whether file is writable
        // if file not exists, create it
        $handle = @fopen($filename, 'c');
        if ($handle === false) {
            throw new RuntimeException("'$filename' is not writable file");
            
        }
        fclose($handle);
        $this->filename = $filename;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function writeLog(string $tag, string $message, array $context = [], $loggerLevel): void
    {
        $out = sprintf(
            "%s [%s] [%s] %s\n",
            date('c'),
            $tag,
            $loggerLevel,
            $message
        );
        @file_put_contents($this->filename, $out, FILE_APPEND | LOCK_EX);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapLevels(): array
    {
        return [
            LogLevel::EMERGENCY => 'emergency',
            LogLevel::ALERT => 'alert',
            LogLevel::CRITICAL => 'critical',
            LogLevel::ERROR => 'error',
            LogLevel::WARNING => 'warning',
            LogLevel::NOTICE => 'notice',
            LogLevel::INFO => 'info',
            LogLevel::DEBUG => 'debug',
        ];
    }
}
