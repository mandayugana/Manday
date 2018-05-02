<?php

namespace Manday\Entity\Hydrator\Strategy;

use Manday\Entity\EntityInterface;

interface StrategyInterface
{
    public function extract($value, EntityInterface $context = null);
    
    public function hydrate($value, array $context = null);
}
