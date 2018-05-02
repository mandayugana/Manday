<?php

namespace MyPos\Object;

interface EqualityComparableInterface
{
    /**
     * Compares object with another
     * 
     * @param \MyPos\Object\EqualityComparableInterface $other Object to compare against.
     * @return bool True if object equals to another. False otherwise.
     */
    public function equals(EqualityComparableInterface $other): bool;
}
