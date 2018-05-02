<?php

namespace Manday\Entity\Repository;

use Manday\Entity\EntityInterface;
use Manday\Entity\EntityAwareInterface;

interface RepositoryInterface extends EntityAwareInterface
{
    /**
     * Finds single entity by ID in repository then build the entity.
     * 
     * @param int $id Unique entity ID. This is usually an auto-increment number.
     * @return EntityInterface The entity.
     */
    public function findById(int $id): EntityInterface;
    
    /**
     * Finds all entities in repository.
     * 
     * @param array $criteria Associative array represents property names and
     * their values. Only entities which properties match supplied criteria
     * will be returned.
     * 
     * Example:
     * 
     * <code>
     * $userRepository->find([
     *     'hometown' => 'Sumedang',
     *     'active' => 1,
     * ])
     * </code>
     * @return array Found entities.
     */
    public function find(array $criteria = []): array;
    
    /**
     * Stores entity into repository.
     * 
     * @param EntityInterface $entity The entity to store.
     * @return EntityInterface Stored entity. This is the complete entity
     * and should be used instead of input entity. After storing new entity,
     * returned entity contains entity ID.
     */
    public function store(EntityInterface $entity): EntityInterface;
    
    /**
     * Removes entity from repository.
     * 
     * @param EntityInterface $entity The entity to delete.
     * @return void
     */
    public function remove(EntityInterface $entity): void;
}
