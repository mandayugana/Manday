<?php

namespace Manday\Log\Logger;

use Manday\Log\LogLevel;
use Manday\Log\Logger\LoggerInterface;

abstract class AbstractLogger implements LoggerInterface
{
    /**
     * {@inheritdoc}
     */
    public function emergency(string $tag, string $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }
    
    /**
     * {@inheritdoc}
     */
    public function alert(string $tag, string $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }
    
    /**
     * {@inheritdoc}
     */
    public function critical(string $tag, string $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }
    
    /**
     * {@inheritdoc}
     */
    public function warning(string $tag, string $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }
    
    /**
     * {@inheritdoc}
     */
    public function error(string $tag, string $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }
    
    /**
     * {@inheritdoc}
     */
    public function notice(string $tag, string $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }
    
    /**
     * {@inheritdoc}
     */
    public function info(string $tag, string $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }
    
    /**
     * {@inheritdoc}
     */
    public function debug(string $tag, string $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }
    
    /**
     * {@inheritdoc}
     */
    abstract public function log(string $tag, string $message, array $context = [], $level = LogLevel::INFO): void;
    
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
