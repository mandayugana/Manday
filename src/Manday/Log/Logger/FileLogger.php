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
    public function log(string $tag, string $message, array $context = [], $level = LogLevel::INFO): void
    {
        $out = sprintf(
            "%s [%s] [%s] %s\n",
            date('c'),
            $tag,
            $level,
            $this->interpolate($message, $context)
        );
        @file_put_contents($this->filename, $out, FILE_APPEND | LOCK_EX);
    }
}
