<?php

namespace Manday\Entity\Hydrator;

use Manday\Entity\Hydrator\Strategy\StrategyInterface;

interface StrategyAwareInterface
{
    public function addStrategy(string $name, StrategyInterface $strategy): void;
    
    public function hasStrategy(string $name): bool;
    
    public function getStrategy(string $name): StrategyInterface;
}
