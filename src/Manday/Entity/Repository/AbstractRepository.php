<?php

namespace Manday\Entity\Repository;

use Manday\Entity\EntityInterface;
use Manday\Entity\EntityAwareTrait;
use Manday\Entity\Hydrator\HydratorInterface;
use Manday\Entity\Repository\RepositoryInterface;
use Manday\Entity\Exception\EntityNotFoundException;

abstract class AbstractRepository implements RepositoryInterface
{   
    use EntityAwareTrait;
    
    /**
     * Hydrator for entities.
     * 
     * @var \Manday\Entity\Hydrator\HydratorInterface
     */
    protected $hydrator;

    /**
     * Name of property that holds entity ID. Usually this is the primary key in
     * database storage.
     *
     * @var string
     */
    protected $idName;
    
    /**
     * In memory cache for retrieved entities from storage.
     * @var type 
     */
    protected $cache = [];
    
    /**
     * Object constructor.
     * 
     * @param HydratorInterface $hydrator Entity hydrator.
     * @param string $entityClassName Allowed class name for each entity in repository.
     */
    public function __construct(HydratorInterface $hydrator, string $entityClassName)
    {
        $this->hydrator = $hydrator;
        $this->setEntityClassName($entityClassName);
        $hydrator->setEntityClassName($entityClassName);
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): EntityInterface
    {
        if (isset($this->cache[$id]) === false) {
            $entities = $this->find([$this->idName => $id]);
            if (count($entities) === 0) {
                throw new EntityNotFoundException(
                    "{$this->entityClassName}::{$this->idName}=$id"
                );
            }
        }
        return $this->cache[$id];
    }
    
    abstract public function find(array $criteria = array()): array;
    
    abstract public function store(EntityInterface $entity): EntityInterface;
    
    abstract public function remove(EntityInterface $entity): void;
    
    /**
     * Caches entities in memory.
     * 
     * @param array $entities Entities to cache.
     * @return array Array of entities keyed by its entity ID.
     */
    protected function cacheEntities(array $entities): array
    {
        $cachedEntities = [];
        foreach ($entities as $entity) {
            $id = $entity->getId();
            if (!isset($this->cache[$id])) {
                $this->cache[$id] = $entity;
            }
            $cachedEntities[$id] = $this->cache[$id];
        }
        return $cachedEntities;
    }

    protected function resetEntityId(EntityInterface $entity, string $idName): void
    {
        $data = [$idName => null];
        $this->hydrator->hydrate($data, $entity);
    }
}
