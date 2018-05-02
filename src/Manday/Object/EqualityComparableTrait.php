<?php

namespace MyPos\Object;

use MyPos\Object\EqualityComparableInterface;
use MyPos\Object\Exception\ComparableDifferentException;

trait EqualityComparableTrait
{
    public function equals(EqualityComparableInterface $other): bool
    {
        if ($other instanceof EqualityComparableTrait) {
            try {
                $this->assertArrayEquality(
                    $this->getEqualityComparableValues(),
                    $other->getEqualityComparableValues()
                );
                return true;
            } catch (ComparableDifferentException $e) {
                return false;
            }
        } else {
            return $this == $other;
        }
    }
    
    protected function assertArrayEquality(array $one, array $two): void
    {
        if (array_values($one) !== array_values($two)) {
            throw new ComparableDifferentException('Different properties');
        }
        
        foreach ($one as $key => $value) {
            $this->assertEquality($value, $two[$key]);
        }
    }
    
    protected function assertEquality($one, $two): void
    {
        if (
            ($one instanceof EqualityComparableInterface)
            && ($two instanceof EqualityComparableInterface)
            && $one->equals($two)
        ) {
            return;
        } elseif ($one == $two) {
            return;
        } elseif (is_array($one) && is_array($two)) {
            $this->assertArrayEquality($one, $two);
            return;
        }
        
        throw new ComparableDifferentException('Different value');
    }
    
    abstract protected function getEqualityComparableValues(): array;
}
