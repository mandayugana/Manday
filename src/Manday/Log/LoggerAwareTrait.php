<?php

namespace Manday\Log;

use Manday\Log\LoggerInterface;

trait LoggerAwareTrait
{
    protected $logger;
    
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
