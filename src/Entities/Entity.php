<?php

namespace Subtext\AppEngine\Entities;

abstract class Entity
{
    protected const TABLE = '';
    protected const PRIMARY_KEY = '';
    protected const COLUMN_MAP = [];
    protected const COLUMN_SEARCH = [];

    public static function getInsertQuery(): string
    {
        $columns = [];
        $values = [];
        $table = static::TABLE;
        $key = static::PRIMARY_KEY;
        foreach (static::COLUMN_MAP as $token => $column) {
            array_push($columns, "`$column`");
            array_push($values, $token);
        }
        $query = <<< SQL
INSERT INTO `$table` 
(X_COLUMNS_X) 
VALUES 
(X_VALUES_X);
SQL;

        return str_replace(
            ['X_COLUMNS_X', 'X_VALUES_X'],
            [implode(", ", $columns), implode(", ", $values)],
            $query
        );
    }

    public static function getSelectQuery(): string
    {
        $table = static::TABLE;
        $key = static::PRIMARY_KEY;
        $query = <<<SQL
SELECT *
FROM `$table`
WHERE `$key` = :id
SQL;

        return $query;
    }

    public static function getUpdateQuery(array $data): string
    {
        $conditions = [];
        $table = static::TABLE;
        $key = static::PRIMARY_KEY;
        foreach ($data as $token => $value) {
            if (isset(static::COLUMN_MAP[$token])) {
                $columnName = static::COLUMN_MAP[$token];
                if ($columnName != $key) {
                    array_push($conditions, "`$columnName` = $token");
                }
            }
        }
        $sql = <<<SQL
UPDATE `$table`
SET X_CONDITIONS_X
WHERE `$key` = :id
SQL;

        return str_replace('X_CONDITIONS_X', implode(",\n", $conditions), $sql);
    }

    public static function getDeleteQuery(): string
    {
        $table = static::TABLE;
        $key = static::PRIMARY_KEY;
        $sql = <<<SQL
DELETE FROM `$table`
WHERE `$key` = :id
SQL;

        return $sql;
    }

    public function getId(): int
    {
        $key = static::PRIMARY_KEY;
        if (!property_exists($this, $key)) {
            throw new \InvalidArgumentException(
                "The PRIMARY_KEY must be set correctly on the Entity class"
            );
        }

        return $this->$key;
    }

    abstract public function getData(): array;
}
