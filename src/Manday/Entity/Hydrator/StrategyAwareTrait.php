<?php

namespace Manday\Entity\Hydrator;

use Manday\Entity\Hydrator\Strategy\StrategyInterface;
use Manday\Entity\Hydrator\Exception\StrategyNotFoundException;

trait StrategyAwareTrait
{
    protected $strategies = [];
    
    public function addStrategy(string $name, StrategyInterface $strategy): void
    {
        $this->strategies[$name] = $strategy;
    }
    
    public function hasStrategy(string $name): bool
    {
        return isset($this->strategies[$name]);
    }
    
    public function getStrategy(string $name): StrategyInterface
    {
        if ($this->hasStrategy($name) === false) {
            throw new StrategyNotFoundException($name);
        }
        return $this->strategies[$name];
    }
}
