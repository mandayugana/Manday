<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Manday\Log\Logger;

use Manday\Log\Logger\AbstractLogger;
use Manday\Log\LogLevel;

/**
 * Description of DrupalLogger
 *
 * @author Manda Yugana
 */
class DrupalLogger extends AbstractLogger
{
    /**
     * {@inheritdoc}
     */
    protected function writeLog(string $tag, string $message, array $context = [], $loggerLevel): void
    {
        if (\VERSION >= '8.0') {
            switch ($loggerLevel) {
                case \WATCHDOG_EMERGENCY:
                    \Drupal::logger($tag)->emergency($message);
                case \WATCHDOG_ALERT:
                    \Drupal::logger($tag)->alert($message);
                case \WATCHDOG_CRITICAL:
                    \Drupal::logger($tag)->critical($message);
                case \WATCHDOG_ERROR:
                    \Drupal::logger($tag)->error($message);
                case \WATCHDOG_WARNING:
                    \Drupal::logger($tag)->warning($message);
                case \WATCHDOG_NOTICE:
                    \Drupal::logger($tag)->notice($message);
                case \WATCHDOG_DEBUG:
                    \Drupal::logger($tag)->debug($message);
                case \WATCHDOG_INFO:
                default:
                    \Drupal::logger($tag)->info($message);
            }
        } elseif (\VERSION >= '6.0') {
            // Drupal 6 and 7
            $link = $context['link'] ?? null;
            watchdog($tag, $message, $[], $loggerLevel, $link);
        } else {
            // Drupal 5 and earlier are not supported
            // because of lack of log severity
            throw new \RuntimeException(sprintf('Drupal %s is not supported', \VERSION));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function mapLevels(): array
    {
        return [
            LogLevel::EMERGENCY => \WATCHDOG_EMERGENCY,
            LogLevel::ALERT => \WATCHDOG_ALERT,
            LogLevel::CRITICAL => \WATCHDOG_CRITICAL,
            LogLevel::ERROR => \WATCHDOG_ERROR,
            LogLevel::WARNING => \WATCHDOG_WARNING,
            LogLevel::NOTICE => \WATCHDOG_NOTICE,
            LogLevel::INFO => \WATCHDOG_INFO,
            LogLevel::DEBUG => \WATCHDOG_DEBUG,
        ];
    }
}
