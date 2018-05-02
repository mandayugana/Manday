<?php

namespace Manday\Entity\Hydrator;

use Manday\Entity\EntityInterface;
use Manday\Entity\EntityAwareTrait;
use Manday\Entity\Hydrator\HydratorInterface;
use Manday\Entity\Hydrator\StrategyAwareInterface;
use Manday\Entity\Hydrator\StrategyAwareTrait;
use Manday\Entity\Hydrator\Exception\StrategyNotFoundException;

abstract class AbstractHydrator implements HydratorInterface, StrategyAwareInterface
{
    use StrategyAwareTrait;
    use EntityAwareTrait;

    public function __construct(string $entityClassName)
    {
        $this->setEntityClassName($entityClassName);
    }
    
    protected function extractValue(string $name, $value, EntityInterface $context = null)
    {
        try {
            $this->getStrategy($name)->extract($value, $context);
        } catch (StrategyNotFoundException $ignored) {
            return $value;
        }
    }
    
    protected function hydrateValue(string $name, $value, array $context = null)
    {
        try {
            $this->getStrategy($name)->hydrate($value, $context);
        } catch (StrategyNotFoundException $ignored) {
            return $value;
        }
    }
}
