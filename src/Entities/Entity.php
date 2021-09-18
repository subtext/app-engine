<?php

namespace Subtext\AppEngine\Entities;

use InvalidArgumentException;

/**
 * Entity
 *
 * @package Subtext\AppEngine\Entities
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */
abstract class Entity
{
    /**
     * The database table name
     */
    protected const TABLE = '';

    /**
     * The database table primary key column name
     */
    protected const PRIMARY_KEY = '';

    /**
     * An array mapping SQL tokens to be used with their counterpart column
     * names when constructing queries.
     */
    protected const COLUMN_MAP = [];

    /**
     * An array containing column names which may be used for search.
     */
    protected const COLUMN_SEARCH = [];

    /**
     * Provides a generic SQL INSERT query for the entity.
     *
     * @return string
     */
    public static function getInsertQuery(): string
    {
        $columns = [];
        $values = [];
        $table = static::TABLE;
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

    /**
     * Provides a generic SQL SELECT query for the entity.
     *
     * @return string
     */
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

    /**
     * Provides a generic SQL UPDATE query for the entity.
     *
     * @param array $data
     * @return string
     */
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

    /**
     * Provides a generic SQL DELETE query for the entity.
     *
     * @return string
     */
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

    /**
     * Returns the PRIMARY_KEY constant for the entity which is required for
     * proper operation of the entity class. I made a change.
     *
     * @return int
     */
    public function getId(): int
    {
        $key = static::PRIMARY_KEY;
        if (!property_exists($this, $key)) {
            throw new InvalidArgumentException(
                "The PRIMARY_KEY must be set correctly on the Entity class"
            );
        }

        return $this->$key;
    }

    /**
     * Returns an array whose keys are the tokens which correspond to the column
     * names in the database. This method would generally use the COLUMN_MAP
     * constant to build the data.
     *
     * @return array
     */
    abstract public function getData(): array;
}
