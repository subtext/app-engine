<?php
namespace Subtext\AppEngine\Entities;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Subtext\AppEngine\Services\Database;

/**
 * @coversDefaultClass \Subtext\AppEngine\Entities\Collection
 */
class CollectionTest extends TestCase
{
    /**
     * A sample Entity class used repeatedly in tests. The id value is not normally
     * passed in using the constructor. This is a contrivance for the sake of
     * unit testing, as a way to return a specific id value from the entity.
     *
     * @param int $getIdValue
     * @return Entity
     */
    protected function getEntityInstance(int $getIdValue = 1): Entity
    {
        return new class($getIdValue) extends Entity {
            protected $id;
            public function __construct($value)
            {
                $this->id = $value;
            }

            public function getId(): int
            {
                return $this->id;
            }

            public function getData(): array
            {
                return [];
            }

            public static function getClassName(): string
            {
                return __CLASS__;
            }

            public static function getInsertQuery(): string
            {
                return "INSERT";
            }

            public static function getSelectQuery(): string
            {
                return "SELECT";
            }

            public static function getUpdateQuery(array $data): string
            {
                return "UPDATE";
            }

            public static function getDeleteQuery(): string
            {
                return "DELETE";
            }
        };
    }

    /**
     * A sample Collection class used repeatedly in these tests. The className is
     * not normally passed into the constructor. This is a device used only for
     * unit testing, as a way to pass the className argument into an anonymous class.
     *
     * @param Database $db
     * @param string $className
     * @return Collection
     */
    protected function getCollectionInstance(Database $db, string $className): Collection
    {
        return new class($db, $className) extends Collection {
            private $className;

            public function __construct(Database $db, string $className)
            {
                parent::__construct($db);
                $this->className = $className;
            }

            protected function getClassName(): string
            {
                return $this->className;
            }
        };
    }

    /**
     * @covers ::__construct
     * @covers ::createEntity
     * @covers ::getEntity
     */
    public function testCanCreateEntity(): void
    {
        $expected = $this->getMockForAbstractClass(Entity::class);
        $db = $this->createMock(Database::class);
        $db->expects($this->once())
            ->method('getIdForInsert')
            ->willReturn(4);
        $db->expects($this->once())
            ->method('getEntity')
            ->willReturn($expected);
        $entity = $this->getEntityInstance();
        $unit = $this->getCollectionInstance($db, $entity::getClassName());
        $actual = $unit->createEntity([]);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers ::__construct
     * @covers ::createEntity
     */
    public function testWillThrowExceptionForEntityNotFound(): void
    {
        $exception = $this->createMock(\RuntimeException::class);
        $entity = $this->getEntityInstance();
        $db = $this->createMock(Database::class);
        $db->expects($this->once())
            ->method('getIdForInsert')
            ->willReturn(0);
        $db->expects($this->once())
            ->method('getErrors')
            ->willReturn([$exception]);
        $unit = $this->getCollectionInstance($db, $entity::getClassName());
        $this->expectException(\RuntimeException::class);
        $unit->createEntity([]);
    }

    /**
     * @covers ::__construct
     * @covers ::createEntity
     */
    public function testWillStillThrowExceptionWithEmptyErrorMessages(): void
    {
        $entity = $this->getEntityInstance();
        $db = $this->createMock(Database::class);
        $db->expects($this->once())
            ->method('getIdForInsert')
            ->willReturn(0);
        $db->expects($this->once())
            ->method('getErrors')
            ->willReturn([]);
        $unit = $this->getCollectionInstance($db, $entity::getClassName());
        $this->expectException(\RuntimeException::class);
        $unit->createEntity([]);
    }

    /**
     * @covers ::__construct
     * @covers ::saveEntity
     */
    public function testCanSaveEntity(): void
    {
        $entity = $this->getEntityInstance();
        $db = $this->createMock(Database::class);
        $db->expects($this->once())
            ->method('getRowsAffectedForUpdate')
            ->willReturn(1);
        $unit = $this->getCollectionInstance($db, $entity::getClassName());
        $this->assertTrue($unit->saveEntity($entity));
    }

    /**
     * @covers ::__construct
     * @covers ::saveEntity
     * @covers ::getErrors
     */
    public function testSaveEntityReturnsTrueForZeroRowsUpdated(): void
    {
        $entity = $this->getEntityInstance();
        $db = $this->createMock(Database::class);
        $db->expects($this->once())
            ->method('getRowsAffectedForUpdate')
            ->willReturn(0);
        $db->expects($this->once())
            ->method('getErrors')
            ->willReturn([]);
        $unit = $this->getCollectionInstance($db, $entity::getClassName());
        $this->assertTrue($unit->saveEntity($entity));
        $this->assertEquals([], $unit->getErrors());
    }

    /**
     * @covers ::__construct
     * @covers ::saveEntity
     * @covers ::getErrors
     */
    public function testSaveEntityWillReturnFalseIfErrorsExist(): void
    {
        $exception = $this->createMock(\PDOException::class);
        $entity = $this->getEntityInstance();
        $db = $this->createMock(Database::class);
        $db->expects($this->once())
            ->method('getRowsAffectedForUpdate')
            ->willReturn(0);
        $db->expects($this->once())
            ->method('getErrors')
            ->willReturn([$exception]);
        $unit = $this->getCollectionInstance($db, $entity::getClassName());
        $this->assertFalse($unit->saveEntity($entity));
        $this->assertSame([$exception], $unit->getErrors());
    }

    /**
     * @covers ::__construct
     * @covers ::deleteEntity
     */
    public function testCanDeleteEntity(): void
    {
        $entity = $this->getEntityInstance();
        $db = $this->createMock(Database::class);
        $db->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $unit = $this->getCollectionInstance($db, $entity::getClassName());
        $this->assertTrue($unit->deleteEntity(1));
    }

    /**
     * @covers ::__construct
     * @covers ::getEntity
     * @covers ::deleteEntity
     */
    public function testWillRemoveEntityFromCollectionOnDelete(): void
    {
        $faker = Factory::create();
        $id = $faker->numberBetween(1, 10);
        $entity = $this->getEntityInstance($id);
        $db = $this->createMock(Database::class);
        $db->expects($this->once())
            ->method('getEntity')
            ->willReturn($entity);
        $db->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $unit = $this->getCollectionInstance($db, $entity::getClassName());
        $unit->getEntity($id);
        $this->assertArrayHasKey($id, $unit);
        $unit->deleteEntity($id);
        $this->assertArrayNotHasKey($id, $unit);
    }

    /**
     * @covers ::__construct
     * @covers ::getEntity
     * @covers ::deleteEntity
     */
    public function testWillNotRemoveEntityInCaseOfDatabaseError(): void
    {
        $faker = Factory::create();
        $id = $faker->numberBetween(10, 50);
        $exception = $this->createMock(\PDOException::class);
        $entity = $this->getEntityInstance($id);
        $db = $this->createMock(Database::class);
        $db->expects($this->once())
            ->method('getEntity')
            ->willReturn($entity);
        $db->expects($this->once())
            ->method('execute')
            ->willReturn(false);
        $db->expects($this->once())
            ->method('getErrors')
            ->willReturn([$exception]);
        $unit = $this->getCollectionInstance($db, $entity::getClassName());
        $unit->getEntity($id);
        $this->assertArrayHasKey($id, $unit);
        $this->assertFalse($unit->deleteEntity($id));
        $this->assertArrayHasKey($id, $unit);
    }
}
