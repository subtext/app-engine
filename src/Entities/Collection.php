<?php

namespace Subtext\AppEngine\Entities;

use Subtext\AppEngine\Services\Database;

abstract class Collection extends \ArrayObject
{
    private $db;
    private $errors = [];

    public function __construct(Database $db)
    {
        parent::__construct([]);
        $this->db = $db;
    }

    public function createEntity(array $data): Entity
    {
        $className = $this->getClassName();
        $sql = $className::getInsertQuery();
        if (empty($id = $this->db->getIdForInsert($sql, $data))) {
            $errors = $this->db->getErrors();
            throw new \RuntimeException(
                "Entity could not be saved to the database",
                500,
                $errors[0]
            );
        }

        return $this->getEntity($id);
    }

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

    public function saveEntity(Entity $entity): bool
    {
        $className = $this->getClassName();
        $data = $entity->getData();
        $sql = $className::getUpdateQuery($data);
        $id = $entity->getId();
        if ($this->offsetExists($id)) {
            $this->offsetSet($id, $entity);
        }
        $result = $this->db->getRowsAffectedForUpdate($sql, $data);
        if ($result === 0) {
            if (!empty($errors = $this->db->getErrors())) {
                array_push($this->errors, ...$errors);
            }
        }

        return $result;
    }

    public function deleteEntity(int $id): bool
    {
        $className = $this->getClassName();
        $sql = $className::getDeleteQuery();
        if (!$result = $this->db->execute($sql, [':id' => $id])) {
            $errors = $this->db->getErrors();
            array_push($this->errors, ...$errors);
        }

        return $result;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    abstract protected function getClassName(): string;
}
