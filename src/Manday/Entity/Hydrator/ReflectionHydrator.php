<?php

namespace Manday\Entity\Hydrator;

use Manday\Entity\EntityInterface;
use Manday\Entity\Hydrator\AbstractHydrator;

class ReflectionHydrator extends AbstractHydrator
{
    /**
     * {@inheritdoc}
     */
    public function extract(EntityInterface $entity): array
    {
        $this->assertEntityClassName($entity);
        $reflection = new \ReflectionClass(get_class($entity));
        
        $data = [];
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $data[$propertyName] = $this->extractValue(
                $propertyName,
                $property->getValue($entity),
                $entity
            );
        }
        return $data;
    }
    
    /**
     * {@inheritdoc}
     */
    public function hydrate(array $data, EntityInterface $entity = null): EntityInterface
    {
        if ($entity === null) {
            $reflection = new \ReflectionClass($this->entityClassName);
            $entity = $reflection->newInstanceWithoutConstructor();
        } else {
            $this->assertEntityClassName($entity);
        }
        
        $reflection = new \ReflectionObject($entity);
        foreach ($data as $propertyName => $value) {
            if ($reflection->hasProperty($propertyName) === false) {
                // skip unknown property
                continue;
            }
            $property = $reflection->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($entity, $this->hydrateValue($propertyName, $value, $data));
        }
        
        return $entity;
    }
}
