<?php

namespace Manday\Entity;

use Manday\Entity\EntityInterface;

interface EntitiesInterface extends \IteratorAggregate, \Countable
{
    /**
     * Adds new entity to current <code>Entities</code>.
     * 
     * @param \Manday\Entity\EntityInterface $entity The entity to add.
     * @return void
     * @throws \Manday\Entity\Exception\InvalidEntityTypeException If supplied
     * entity type is invalid. Entity is an abstraction. There may be any number
     * of entity types. <code>Entities</code> may restrict entities added into
     * it.
     */
    public function add(EntityInterface $entity): void;
    
    /**
     * Gets an entity at specific index.
     * 
     * @param int $index Index of entity in the list.
     * @return EntityInterface The entity at requested index.
     * @throws \Manday\Entity\Exception\EntityNotfoundException If index is
     * invalid.
     */
    public function get(int $index): EntityInterface;
    
    /**
     * Merges current <code>Entities</code> with another.
     * 
     * @param \Manday\Entity\EntitiesInterface $entities <code>Entities</code>
     * to merge with.
     * @return \Manday\Entity\EntitiesInterface Merged <code>Entities</code>.
     */
    public function merge(EntitiesInterface $entities): EntitiesInterface;
    
    public function contains(EntityInterface $entity): bool;
}
