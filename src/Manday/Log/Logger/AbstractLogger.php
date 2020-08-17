<?php

namespace Manday\Log\Logger;

use InvalidArgumentException;
use Manday\Log\LogLevel;
use Manday\Log\Logger\LoggerInterface;

abstract class AbstractLogger implements LoggerInterface
{
    /**
     * {@inheritdoc}
     */
    public function emergency(string $tag, string $message, array $context = []): void
    {
        $this->log($tag, $message, $context, LogLevel::EMERGENCY);
    }
    
    /**
     * {@inheritdoc}
     */
    public function alert(string $tag, string $message, array $context = []): void
    {
        $this->log($tag, $message, $context, LogLevel::ALERT);
    }
    
    /**
     * {@inheritdoc}
     */
    public function critical(string $tag, string $message, array $context = []): void
    {
        $this->log($tag, $message, $context, LogLevel::CRITICAL);
    }
    
    /**
     * {@inheritdoc}
     */
    public function warning(string $tag, string $message, array $context = []): void
    {
        $this->log($tag, $message, $context, LogLevel::WARNING);
    }
    
    /**
     * {@inheritdoc}
     */
    public function error(string $tag, string $message, array $context = []): void
    {
        $this->log($tag, $message, $context, LogLevel::ERROR);
    }
    
    /**
     * {@inheritdoc}
     */
    public function notice(string $tag, string $message, array $context = []): void
    {
        $this->log($tag, $message, $context, LogLevel::NOTICE);
    }
    
    /**
     * {@inheritdoc}
     */
    public function info(string $tag, string $message, array $context = []): void
    {
        $this->log($tag, $message, $context, LogLevel::INFO);
    }
    
    /**
     * {@inheritdoc}
     */
    public function debug(string $tag, string $message, array $context = []): void
    {
        $this->log($tag, $message, $context, LogLevel::DEBUG);
    }
    
    /**
     * {@inheritdoc}
     */
    public function log(string $tag, string $message, array $context = [], int $level = LogLevel::INFO): void
    {
        $levels = $this->mapLevels();
        if (isset($levels[$level]) === false) {
            throw new InvalidArgumentException("Invalid log level: $level");
        }

        $this->writeLog(
            $tag,
            $this->interpolate($message, $context),
            $context,
            $levels[$level]
        );
    }

    /**
     * Write log.
     * 
     * @param string $tag Log tag.
     * @param string $message Message to be written to log.
     * @param array $context Additional context of the log.
     * @param mixed $loggerLevel Severity of the log, specific to the logger backend.
     * @return void
     */
    abstract protected function writeLog(string $tag, string $message, array $context = [], $loggerLevel): void;

    /**
     * Maps internal log level to logger's log level.
     * 
     * @return array Map internal log level to logger's log level.
     */
    protected function mapLevels(): array
    {
        return [
            LogLevel::EMERGENCY => LogLevel::EMERGENCY,
            LogLevel::ALERT => LogLevel::ALERT,
            LogLevel::CRITICAL => LogLevel::CRITICAL,
            LogLevel::ERROR => LogLevel::ERROR,
            LogLevel::WARNING => LogLevel::WARNING,
            LogLevel::NOTICE => LogLevel::NOTICE,
            LogLevel::INFO => LogLevel::INFO,
            LogLevel::DEBUG => LogLevel::DEBUG,
        ];
    }
    
    /**
     * Apply context to log message.
     * 
     * @param string $message Log message.
     * @param array $context The context.
     * @return string Context-applied log message.
     */
    protected function interpolate(string $message, array $context = []): string
    {
        // build a replacement array with braces around the context keys
        $replace = [];
        foreach ($context as $key => $value) {
            // check that the value can be casted to string
            if ($this->isValidContextName($key) && $this->isString($value)) {
                $replace['{' . $key . '}'] = (string) $value;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
    
    /**
     * Checks whether a string is a valid context name or not.
     * 
     * @param string $name String to be checked.
     * @return bool True if supplied string is a valid context name. False
     * otherwise.
     */
    protected function isValidContextName(string $name): bool
    {
        // only allow a-z, A-Z, 0-9, underscore, and period.
        // in this case, regex is faster than characters comparison
        return !preg_match('/[^\d\w\.]/', $name);
    }
    
    /**
     * Checks whether context contains exception or not.
     * 
     * @param array $context The context.
     * @return bool True if context contains exception. False otherwise.
     */
    protected function contextHasException(array $context): bool
    {
        return isset($context['exception']) && ($context['exception'] instanceof \Exception);
    }
    
    /**
     * Checks whether a value is (convertable to) string or not.
     * 
     * @param mixed $value Value to be checked.
     * @return bool True if the value is (convertable to) string. False otherwise.
     */
    private function isString($value): bool
    {
        return is_string($value)
            || (is_object($value) && method_exists($value, '__toString'));
    }
}
