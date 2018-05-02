<?php

namespace Manday\Entity\Repository;

use Manday\Entity\EntityInterface;
use Manday\Entity\Exception\NonPersistentEntityException;
use Manday\Entity\Hydrator\HydratorInterface;
use Manday\Entity\Repository\AbstractRepository;

abstract class AbstractDrupalRepository extends AbstractRepository
{
    /**
     * Table name of entity.
     *
     * @var string
     */
    protected $tableName;
    
    public function __construct(HydratorInterface $hydrator, string $entityClassName)
    {
        parent::__construct($hydrator, $entityClassName);
    }
    
    public function find(array $criteria = []): array
    {
        $result = db_select($this->tableName)
            ->fields($this->tableName, $criteria)
            ->execute();
        
        $entities = [];
        while ($data = $result->fetchAllAssoc($this->idName)) {
            $entities[] = $this->hydrator->hydrate($data);
        }
        return $this->cacheEntities($entities);
    }
    
    public function store(EntityInterface $entity): EntityInterface
    {
        // ensure entity type is correct
        $this->assertEntityClassName($entity);
        
        $data = $this->hydrator->extract($entity);
        try {
            // try update
            $this->assertEntityPersistent($entity);
            drupal_write_record($this->tableName, $data, $this->idName);
        } catch (NonPersistentEntityException $ignored) {
            // insert
            drupal_write_record($this->tableName, $data);
        }
        $this->hydrator->hydrate($data, $entity);
        $this->cacheEntities([$entity]);
        return $entity;
        
    }
    
    public function remove(EntityInterface $entity): void
    {
        // ensure entity type is correct
        $this->assertEntityClassName($entity);
        
        // ensure entity exists
        $this->assertEntityPersistent($entity);
        
        // delete from storage
        $id = $entity->getId();
        db_delete($this->tableName)
            ->condition($this->idName, $id)
            ->execute();
        
        // delete from cache
        unset($this->cache[$id]);
        
        // delete ID from entity
        $this->resetEntityId($entity, $this->idName);
    }
}
