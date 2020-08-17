<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manday\Log\Logger;

use Manday\Log\Logger\AbstractLogger;
use Manday\Log\LogLevel;
use Manday\Log\Exception\InvalidArgumentException;

/**
 * Description of DrupalLogger
 *
 * @author Manda Yugana
 */
class DrupalLogger extends AbstractLogger
{
    protected $map = [
        LogLevel::EMERGENCY => \WATCHDOG_EMERGENCY,
        LogLevel::ALERT => \WATCHDOG_ALERT,
        LogLevel::CRITICAL => \WATCHDOG_CRITICAL,
        LogLevel::ERROR => \WATCHDOG_ERROR,
        LogLevel::WARNING => \WATCHDOG_WARNING,
        LogLevel::NOTICE => \WATCHDOG_NOTICE,
        LogLevel::INFO => \WATCHDOG_INFO,
        LogLevel::DEBUG => \WATCHDOG_DEBUG,
    ];
    
    /**
     * {@inheritdoc}
     */
    public function log(string $tag, string $message, array $context = array(), $level = loglevel::INFO): void
    {
        if (!isset($this->map[$level])) {
            throw new InvalidArgumentException($level);
        }
        
        $interpolatedMessage = $this->interpolate($message, $context);
        $severity = $this->map[$level];
        $link = $context['link'] ?? null;
        
        if (\VERSION >= '8.0') {
            \Drupal::logger($tag)->$level($interpolatedMessage);
        } elseif (\VERSION >= '6.0') {
            // Drupal 6 and 7
            watchdog($tag, $interpolatedMessage, $context, $severity, $link);
        } else {
            // Drupal 5 and earlier are not supported
            // because of lack of log severity
            throw new \RuntimeException(sprintf('Drupal %s is not supported', \VERSION));
        }
    }
}
