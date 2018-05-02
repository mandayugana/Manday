<?php

namespace Manday\Entity\Hydrator;

use Manday\Entity\EntityInterface;
use Manday\Entity\EntityAwareInterface;

interface HydratorInterface extends EntityAwareInterface
{
    /**
     * Extracts all properties in an entity to an array.
     * 
     * @param \Manday\Entity\EntityInterface $entity Entity whose values to
     * extract from.
     * @return array Associative array containing values of all entity's
     * properties.
     * @throws \Manday\Entity\Exception\InvalidEntityTypeException If entity
     * is not instance of class that has been set up with setEntityClassName().
     */
    public function extract(EntityInterface $entity): array;
    
    /**
     * Generates an entity object whose property values supplied from given data.
     * 
     * @param array $data Set of property name and its value.
     * @param \Manday\Entity\EntityInterface $entity Entity object. Usually,
     * this is an empty entity object created by Reflection. If none supplied,
     * An instance will be created according to allowed class name that was set
     * by constructor.
     * @return \Manday\Entity\EntityInterface Generated entity object.
     * @throws \Manday\Entity\Exception\InvalidEntityTypeException If entity
     * is not instance of class that has been set up with setEntityClassName().
     */
    public function hydrate(array $data, EntityInterface $entity = null): EntityInterface;
}
