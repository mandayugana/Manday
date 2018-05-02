<?php

namespace Manday\Entity;

interface EntityInterface
{
    /**
     * Gets entity's unique ID. This is usually auto-increment number.
     * 
     * @return int|null Entity ID. Null if entity is not yet persistent.
     */
    public function getId(): ?int;
    
    public function equals(EntityInterface $entity): bool;
}
