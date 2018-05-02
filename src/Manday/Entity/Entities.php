<?php

namespace Manday\Entity;

use Manday\Entity\EntityInterface;
use Manday\Entity\EntitiesInterface;
use Manday\Entity\Exception\EntityNotFoundException;
use Manday\Entity\Exception\InvalidEntityTypeException;

class Entities implements EntitiesInterface
{
    /**
     * Allowed entity class name. Not setting this allows any entities added
     * into current <code>Entities</code>.
     * 
     * @var string
     */
    protected $allowedClassName;
    
    /**
     * List of entities.
     * 
     * @var array
     */
    protected $entities = [];
    
    /**
     * Object constructor. <code>Entities</code> may restrict entities added
     * into it.
     * 
     * @param string $allowedClassName Allowed class name.
     */
    public function __construct(string $allowedClassName = EntityInterface::class)
    {
        if (class_exists($allowedClassName) === false) {
            // class not found
            throw new \RuntimeException(
                sprintf('Class %s not found', $allowedClassName)
            );
        }
        
        if (in_array($allowedClassName, class_implements(EntityInterface::class)) === false) {
            // class doesn't implements EntityInterface
            throw new \RuntimeException(
                sprintf('Class %s doesn\'t implement %s', $allowedClassName, EntityInterface::class)
            );
        }
        
        $this->allowedClassName = $allowedClassName;
    }
    
    /**
     * {@inheritdoc}
     */
    public function add(EntityInterface $entity): EntitiesInterface
    {
        if ($this->allowedClassName && ($entity instanceof $this->allowedClassName) === false) {
            throw new InvalidEntityTypeException(
                sprintf('Expect %s, but %s given', $this->allowedClassName, get_class($entity))
            );
        }
        
        if ($this->contains($entity)) {
            throw new \UnexpectedValueException('Duplicate entity');
        }
        
        $this->entities[] = $entity;
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function get(int $index): EntityInterface
    {
        if (isset($this->entities[$index]) === false) {
            throw new EntityNotFoundException('Invalid index: ' . $index);
        }
        return $this->entities[$index];
    }
    
    public function merge(EntitiesInterface $entities): EntitiesInterface
    {
        $newEntities = clone $this;
        foreach ($entities as $entity) {
            $newEntities->add($entity);
        }
        return $newEntities;
    }
    
    public function contains(EntityInterface $entity): bool
    {
        foreach ($this->entities as $e) {
            if ($entity->equals($e)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->entities as $key => $entity) {
            yield $key => $entity;
        }
    }
    
    /**
     * Counts entities.
     * 
     * @return int Number of entities.
     */
    public function count(): int
    {
        return count($this->entities);
    }
}
