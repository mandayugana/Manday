<?php

namespace Manday\Entity\Hydrator\Strategy;

use Manday\Entity\Hydrator\Strategy\StrategyInterface;
use Manday\Entity\EntityInterface;

class SerializeStrategy implements StrategyInterface
{
    public function extract($value, EntityInterface $context = null)
    {
        return serialize($value);
    }
    
    public function hydrate($value, array $context = null)
    {
        return unserialize($value);
    }
}
