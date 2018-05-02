<?php

namespace Manday\Entity\Repository;

use Manday\Database\PDO as MandayPDO;
use Manday\Entity\EntityInterface;
use Manday\Entity\Exception\NonPersistentEntityException;
use Manday\Entity\Repository\AbstractRepository;
use Manday\Entity\Hydrator\HydratorInterface;

abstract class AbstractDbRepository extends AbstractRepository
{
    /**
     * Database connection.
     * 
     * @var \Manday\Database\PDO
     */
    protected $database;

    /**
     * Table name where entity data resides in.
     * 
     * @var string
     */
    protected $tableName;



    /**
     * Constructor
     * 
     * @param \Manday\Database\PDO $database
     * @param \Manday\Entity\Hydrator\HydratorInterface $hydrator
     * @param string $entityClassName
     */
    public function __construct(MandayPDO $database, HydratorInterface $hydrator, string $entityClassName)
    {
        parent::__construct($hydrator, $entityClassName);
        $this->database = $database;
    }

    /**
     * {@inheritdoc}
     * 
     * @todo Optimize hydration for large number of entities.
     */
    public function find(array $criteria = []): array
    {
        $select = $this->database->prepare($this->buildSelectQuery($criteria));
        $select->execute($criteria);
        
        $entities = [];
        while ($data = $select->fetch(MandayPDO::FETCH_ASSOC)) {
            $entities[] = $this->hydrator->hydrate($data);
        }
        return $this->cacheEntities($entities);
    }

    /**
     * {@inheritdoc}
     */
    public function store(EntityInterface $entity): EntityInterface
    {
        // ensure entity type is correct
        $this->assertEntityClassName($entity);

        $data = $this->hydrator->extract($entity);
        try {
            // try update
            $this->assertEntityPersistent($entity);
            $this->database
                ->prepare($this->buildUpdateQuery($data))
                ->execute($data);
        } catch (NonPersistentEntityException $ignored) {
            // insert
            $this->database
                ->prepare($this->buildInsertQuery($data))
                ->execute($data);
            
            // update saved entity ID
            $data[$this->idName] = $this->database->lastInsertId();
        }
        $this->hydrator->hydrate($data, $entity);
        $this->cacheEntities([$entity]);
        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(EntityInterface $entity): void
    {
        // ensure entity type is correct
        $this->assertEntityClassName($entity);
        
        // ensure entity exists
        $this->assertEntityPersistent($entity);
        
        // delete from database
        $id = $entity->getId();
        $delete = $this->database->prepare(
            "DELETE FROM `{$this->tableName}` WHERE `{$this->idName}` = \":id\""
        );
        $delete->execute(['id' => $id]);
        
        // delete from cache
        unset($this->cache[$id]);
        
        // delete ID from entity
        $this->resetEntityId($entity, $this->idName);
    }

    protected function buildUpdateQuery(array $data): string
    {
        $fieldAndValues = implode(', ', array_map(function (string $name) {
            return "`$name` = \":$name\"";
        }, array_keys($data)));
        
        return "UPDATE `{$this->tableName}` SET " . $fieldAndValues
            . " WHERE `{$this->idName}` = \":{$this->idName}\"";
    }

    protected function buildInsertQuery(array $data): string
    {
        $fields = implode(', ', array_map(function (string $name) {
            return "`$name`";
        }, array_keys($data)));
        
        $valuePlaceholders = implode(', ', array_map(function (string $name) {
            return "\":$name\"";
        }, array_keys($data)));
        
        return "INSERT INTO `{$this->tableName}` ($fields)"
            . " VALUES ($valuePlaceholders)";
    }
    
    protected function buildSelectQuery(array $criteria): string
    {
        // retrieve data from database
        $sql = "SELECT * FROM `{$this->tableName}`";
        if ($criteria) {
            // assert field names
            $where = [];
            foreach (array_keys($criteria) as $field) {
                $where[] = "`$field` = \":$field\"";
            }
            if ($where) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
        }
        
        return $sql;
    }
}
