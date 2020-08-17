<?php

namespace Manday\Log\Logger;

use Manday\Log\LogLevel;
use Manday\Log\Exception\InvalidArgumentException;

/**
 * Describes a logger instance.
 *
 * The message MUST be a string or object implementing __toString().
 *
 * The message MAY contain placeholders in the form: {foo} where foo
 * will be replaced by the context data in key "foo".
 *
 * The context array can contain arbitrary data, the only assumption that
 * can be made by implementors is that if an Exception instance is given
 * to produce a stack trace, it MUST be in a key named "exception".
 *
 * See https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 * for the full interface specification.
 */
interface LoggerInterface
{
    /**
     * System is unusable.
     *
     * @param string $tag Log tag.
     * @param string $message Log message.
     * @param array $context Context of this log entry. This is an associative
     * array with context name as its keys.
     * @return void
     */
    public function emergency(string $tag, string $message, array $context = []): void;

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $tag Log tag.
     * @param string $message Log message.
     * @param array $context Context of this log entry. This is an associative
     * array with context name as its keys.
     * @return void
     */
    public function alert(string $tag, string $message, array $context = []): void;

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $tag Log tag.
     * @param string $message Log message.
     * @param array $context Context of this log entry. This is an associative
     * array with context name as its keys.
     * @return void
     */
    public function critical(string $tag, string $message, array $context = []): void;

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $tag Log tag.
     * @param string $message Log message.
     * @param array $context Context of this log entry. This is an associative
     * array with context name as its keys.
     * @return void
     */
    public function error(string $tag, string $message, array $context = []): void;

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $tag Log tag.
     * @param string $message Log message.
     * @param array $context Context of this log entry. This is an associative
     * array with context name as its keys.
     * @return void
     */
    public function warning(string $tag, string $message, array $context = []): void;

    /**
     * Normal but significant events.
     *
     * @param string $tag Log tag.
     * @param string $message Log message.
     * @param array $context Context of this log entry. This is an associative
     * array with context name as its keys.
     * @return void
     */
    public function notice(string $tag, string $message, array $context = []): void;

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $tag Log tag.
     * @param string $message Log message.
     * @param array $context Context of this log entry. This is an associative
     * array with context name as its keys.
     * @return void
     */
    public function info(string $tag, string $message, array $context = []): void;

    /**
     * Detailed debug information.
     *
     * @param string $tag Log tag.
     * @param string $message Log message.
     * @param array $context Context of this log entry. This is an associative
     * array with context name as its keys.
     * @return void
     */
    public function debug(string $tag, string $message, array $context = []): void;

    /**
     * Logs with an arbitrary level.
     * 
     * Log message may contain tokens that represent the context name surrounded
     * by curly braces. Those tokens will be replaced by context value.
     * 
     * Example:
     * 
     * <code>
     * $logger->log(
     *     'theTag',
     *     'Content created by {creator}',
     *     ['creator' => 'Ujang'],
     *     \Manday\Log\LogLevel::INFO
     * ); // log message becomes: "Content created by Ujang"
     * </code>
     *
     * @param string $tag Log tag.
     * @param string $message Message to be written to log. Message may contains
     * variables which will be replaced by values in $context.
     * @param array $context Context of this log entry. This is an associative
     * array with context name as its keys.
     * @param int $level Severity of the log.
     * @throws \Manday\Log\Exception\InvalidArgumentException If log level is not valid.
     * @return void
     */
    public function log(string $tag, string $message, array $context = [], int $level = LogLevel::INFO): void;
}
