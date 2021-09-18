<?php

namespace Subtext\AppEngine\Entities;

use ArrayObject;
use RuntimeException;
use Subtext\AppEngine\Services\Database;

/**
 * Collection
 *
 * @package Subtext\AppEngine\Entities
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */
abstract class Collection extends ArrayObject
{
    private Database $db;
    private array $errors = [];

    public function __construct(Database $db)
    {
        parent::__construct([]);
        $this->db = $db;
    }

    /**
     * Create (CRUD) an entity and persist it in the database. The entity is
     * added to the collection.
     *
     * @param array $data
     * @return Entity
     */
    public function createEntity(array $data): Entity
    {
        $className = $this->getClassName();
        $sql = $className::getInsertQuery();
        if (empty($id = $this->db->getIdForInsert($sql, $data))) {
            $errors = $this->db->getErrors();
            if (!empty($errors)) {
                $previous = $errors[0];
            } else {
                $previous = null;
            }
            throw new RuntimeException(
                "Entity could not be saved to the database",
                500,
                $previous
            );
        }

        return $this->getEntity($id);
    }

    /**
     * Read (CRUD) an entity's data from the database and add it to the
     * collection or update an existing entity.
     *
     * @param int $id
     * @return Entity
     */
    public function getEntity(int $id): Entity
    {
        if (!$this->offsetExists($id)) {
            $className = $this->getClassName();
            $this->offsetSet($id, $this->db->getEntity(
                $className::getSelectQuery(),
                [':id' => $id],
                $className
            ));
        }

        return $this->offsetGet($id);
    }

    /**
     * Update (CRUD) persist any changes in an entity back to the database.
     *
     * @param Entity $entity
     * @return bool
     */
    public function saveEntity(Entity $entity): bool
    {
        $className = $this->getClassName();
        $data = $entity->getData();
        $sql = $className::getUpdateQuery($data);
        $id = $entity->getId();
        if (!$this->offsetExists($id)) {
            $this->offsetSet($id, $entity);
        }
        $result = $this->db->getRowsAffectedForUpdate($sql, $data);
        if ($result === 0) {
            if (!empty($errors = $this->db->getErrors())) {
                array_push($this->errors, ...$errors);
            } else {
                $result = true;
            }
        }

        return boolval($result);
    }

    /**
     * Delete (CRUD) an entity from the database and remove it from the collection.
     *
     * @param int $id
     * @return bool
     */
    public function deleteEntity(int $id): bool
    {
        $className = $this->getClassName();
        $sql = $className::getDeleteQuery();
        if (!$result = $this->db->execute($sql, [':id' => $id])) {
            $errors = $this->db->getErrors();
            array_push($this->errors, ...$errors);
        } else {
            if ($this->offsetExists($id)) {
                unset($this[$id]);
            }
        }

        return $result;
    }

    /**
     * Get an array of all errors that have occurred.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * A method which returns the class name of an Entity to which the
     * collection is bound.
     *
     * @return string
     */
    abstract protected function getClassName(): string;
}
