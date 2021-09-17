<?php
namespace Subtext\AppEngine\Entities;

use Faker\Factory;
use PHPUnit\Framework\TestCase;

/**
 * Class EntityTest
 *
 * @package Subtext\AppEngine\Base
 * @coversDefaultClass \Subtext\AppEngine\Entities\Entity
 */
class EntityTest extends TestCase
{
    /**
     * @covers ::getInsertQuery
     */
    public function testCanGetInsertQuery(): void
    {
        $expected = "INSERT INTO `tables` 
(`table_title`, `table_name`, `table_alias`) 
VALUES 
(:title, :name, :alias);";
        $unit = new class() extends Entity {
            protected const TABLE = 'tables';
            protected const PRIMARY_KEY = 'table_id';
            protected const COLUMN_MAP  = [
                ':title' => 'table_title',
                ':name' => 'table_name',
                ':alias' => 'table_alias',
            ];

            public function getData(): array
            {
                return [];
            }
        };
        $actual = $unit::getInsertQuery();
        $this->assertEquals($actual, $expected);
    }

    /**
     * @covers ::getSelectQuery
     */
    public function testCanGetSelectQuery(): void
    {
        $expected = "SELECT *
FROM `tables`
WHERE `table_id` = :id";
        $unit = new class() extends Entity {
            protected const TABLE = 'tables';
            protected const PRIMARY_KEY = 'table_id';
            protected const COLUMN_MAP  = [
                ':title' => 'table_title',
                ':name' => 'table_name',
                ':alias' => 'table_alias',
            ];

            public function getData(): array
            {
                return [];
            }
        };
        $actual = $unit::getSelectQuery();
        $this->assertEquals($actual, $expected);
    }

    /**
     * @covers ::getUpdateQuery
     */
    public function testCanGetUpdateQuery(): void
    {
        $unit = new class() extends Entity {
            protected const TABLE = 'tables';
            protected const PRIMARY_KEY = 'table_id';
            protected const COLUMN_MAP  = [
                ':title' => 'table_title',
                ':name' => 'table_name',
                ':alias' => 'table_alias',
            ];

            public function getData(): array
            {
                return [
                    ':id' => 1,
                    ':title' => 'Alpha',
                    ':name' => 'Beta',
                    ':alias' => 'gamma',
                ];
            }
        };
        $actual = $unit::getUpdateQuery($unit->getData());
        $this->assertStringContainsString("UPDATE `tables`", $actual);
        $this->assertStringContainsString("`table_title` = :title", $actual);
        $this->assertStringContainsString("`table_alias` = :alias", $actual);
    }

    /**
     * @covers ::getDeleteQuery
     */
    public function testCanGetDeleteQuery(): void
    {
        $unit = new class() extends Entity {
            protected const TABLE = 'tables';
            protected const PRIMARY_KEY = 'table_id';
            protected const COLUMN_MAP  = [
                ':title' => 'table_title',
                ':name' => 'table_name',
                ':alias' => 'table_alias',
            ];

            public function getData(): array
            {
                return [
                    ':id' => 1,
                    ':title' => 'Alpha',
                    ':name' => 'Beta',
                    ':alias' => 'gamma',
                ];
            }
        };
        $expected = "DELETE FROM `tables`
WHERE `table_id` = :id";
        $actual = $unit::getDeleteQuery();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers ::getId
     */
    public function testCanCreateEntity(): void
    {
        $faker = Factory::create();
        $expected = $faker->numberBetween(1, 10);
        $unit = new class($expected) extends Entity {
            protected const TABLE = 'table';
            protected const PRIMARY_KEY = 'id';
            protected int $id;

            public function __construct(int $id) {
                $this->id = $id;
            }

            public function getData(): array
            {
                return [':id' => $this->getId()];
            }
        };
        $actual = $unit->getData()[':id'];
        $this->assertEquals($actual, $expected);
    }

    /**
     * @covers ::getId
     */
    public function testWillThrowExceptionIfImproperlyConfigured(): void
    {
        $unit = new class() extends Entity {
            public function getData(): array
            {
                return [];
            }
        };
        $this->expectException(\InvalidArgumentException::class);
        $unit->getId();
    }
}