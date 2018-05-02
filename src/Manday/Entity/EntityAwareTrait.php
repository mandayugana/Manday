<?php

namespace Manday\Entity;

use Manday\Entity\EntityInterface;
use Manday\Entity\Exception\InvalidEntityTypeException;
use Manday\Entity\Exception\NonPersistentEntityException;

trait EntityAwareTrait
{
    protected $entityClassName;
    
    public function setEntityClassName(string $className): void
    {
        $this->entityClassName = $className;
    }
    
    protected function assertEntityClassName(EntityInterface $entity): void
    {
        $class = get_class($entity);
        if ($class !== $this->entityClassName) {
            throw new InvalidEntityTypeException(
                "{$this->entityClassName} expected, but $class given"
            );
        }
    }
    
    protected function assertEntityPersistent(EntityInterface $entity): void
    {
        if ($entity->getId() === null) {
            throw new NonPersistentEntityException();
        }
    }
}
