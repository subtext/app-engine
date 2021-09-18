<?php

namespace Subtext\AppEngine\Services;

use Faker\Factory;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Subtext\AppEngine\Entities\Entity;
use TypeError;

/**
 * @coversDefaultClass \Subtext\AppEngine\Services\Database
 */
class DatabaseTest extends TestCase
{
    /**
     * @covers ::getInstance
     * @covers ::hasPDO
     * @covers ::resetInstance
     * @covers ::__construct
     */
    public function testCanGetSingletonInstance(): void
    {
        $pdo = $this->createMock(PDO::class);
        $unit = Database::getInstance($pdo);
        $unit2 = Database::getInstance();
        $this->assertSame($unit, $unit2);
        Database::resetInstance();
        $unit3 = Database::getInstance($pdo);
        $this->assertNotSame($unit, $unit3);
    }

    /**
     * @covers ::hasPDO
     * @covers ::getInstance
     * @covers ::resetInstance
     * @covers ::__construct
     */
    public function testWillThrowExceptionForMissingPDO(): void
    {
        Database::resetInstance();
        $this->assertFalse(Database::hasPDO());
        $this->expectException(\RuntimeException::class);
        Database::getInstance();
    }

    /**
     * @covers ::__construct
     * @covers ::getObject
     * @covers ::executeStatement
     * @covers ::getInstance
     * @covers ::getPreparedStatement
     * @covers ::getResultFromDatabase
     * @covers ::hasPDO
     * @covers ::resetInstance
     */
    public function testCanGetObjectResultFromDatabase(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(new \stdClass());
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->atLeastOnce())
            ->method('prepare')
            ->willReturn($stmt);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        $unit->getObject('', []);
    }


    /**
     * @covers ::__construct
     * @covers ::executeStatement
     * @covers ::getInstance
     * @covers ::getObject
     * @covers ::getPreparedStatement
     * @covers ::getResultFromDatabase
     * @covers ::handleError
     * @covers ::hasPDO
     * @covers ::resetInstance
     */
    public function testGetResultFromDatabaseWillCatchThrowables(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willThrowException(new TypeError());
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        $this->expectException(TypeError::class);
        $unit->getObject('', []);
    }

    /**
     * @covers ::__construct
     * @covers ::hasPDO
     * @covers ::getArrayOfObjects
     * @covers ::executeStatement
     * @covers ::getInstance
     * @covers ::resetInstance
     * @covers ::getPreparedStatement
     * @covers ::getResultFromDatabase
     */
    public function testCanGetArrayOfObjectsResultFromDatabase(): void
    {
        $faker = Factory::create();
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([new \stdClass()]);
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        $sql = $faker->sentence();
        $unit->getArrayOfObjects($sql, []);
    }

    /**
     * @covers ::__construct
     * @covers ::executeStatement
     * @covers ::getAssociativeArray
     * @covers ::getInstance
     * @covers ::getPreparedStatement
     * @covers ::getResultFromDatabase
     * @covers ::hasPDO
     * @covers ::resetInstance
     */
    public function testCanGetAssociativeArrayResultFromDatabase(): void
    {
        $faker = Factory::create();
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn([new \stdClass()]);
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        $sql = $faker->sentence();
        $unit->getAssociativeArray($sql, []);
    }

    /**
     * @covers ::__construct
     * @covers ::executeStatement
     * @covers ::getArrayOfAssociativeArrays
     * @covers ::getInstance
     * @covers ::getPreparedStatement
     * @covers ::getResultFromDatabase
     * @covers ::hasPDO
     * @covers ::resetInstance
     */
    public function testCanGetArrayOfAssociativeArrayResultFromDatabase(): void
    {
        $faker = Factory::create();
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([new \stdClass()]);
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        $sql = $faker->sentence();
        $unit->getArrayOfAssociativeArrays($sql, []);
    }

    /**
     * @covers ::__construct
     * @covers ::executeStatement
     * @covers ::getArrayOfAssociativeArrays
     * @covers ::getInstance
     * @covers ::getPreparedStatement
     * @covers ::getResultFromDatabase
     * @covers ::hasPDO
     * @covers ::resetInstance
     */
    public function testGetResultFromDatabaseWillHandleErrors(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(false);
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        $this->expectException(TypeError::class);
        $unit->getArrayOfAssociativeArrays('', []);
    }

    /**
     * @covers ::__construct
     * @covers ::executeStatement
     * @covers ::getEntity
     * @covers ::getInstance
     * @covers ::getPreparedStatement
     * @covers ::getResultFromDatabase
     * @covers ::hasPDO
     * @covers ::resetInstance
     */
    public function testCanGetEntity(): void
    {
        $faker = Factory::create();
        $entity = new class() extends Entity
        {
            public function getData(): array
            {
                return [];
            }

            public static function getClassName(): string
            {
                return __CLASS__;
            }
        };
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('fetchObject')
            ->willReturn($entity);
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        $sql = $faker->sentence();
        $unit->getEntity($sql, [], $entity::getClassName());
    }

    /**
     * @covers ::__construct
     * @covers ::executeStatement
     * @covers ::getEntity
     * @covers ::getInstance
     * @covers ::getPreparedStatement
     * @covers ::handleError
     * @covers ::hasPDO
     * @covers ::resetInstance
     */
    public function testWillLogErrorsOnGetEntityFailure(): void
    {
        $faker = Factory::create();
        $entity = new class() extends Entity
        {
            public function getData(): array
            {
                return [];
            }

            public static function getClassName(): string
            {
                return __CLASS__;
            }
        };
        $stmt1 = $this->createMock(PDOStatement::class);
        $stmt1->expects($this->once())
            ->method('execute')
            ->will($this->throwException(new \PDOException(
                "There was an error in the SQL query"
            )));
        $stmt2 = $this->createMock(PDOStatement::class);
        $stmt2->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt2->expects($this->once())
            ->method('fetchObject')
            ->willThrowException(new TypeError());
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->atLeastOnce())
            ->method('prepare')
            ->willReturnOnConsecutiveCalls($stmt1, $stmt2);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        try {
            $sql = $faker->sentence();
            $unit->getEntity($sql, [], $entity::getClassName());
        } catch (TypeError $e) {
            $this->assertInstanceOf(TypeError::class, $e);
        }
        try {
            $sql = $faker->sentence();
            $unit->getEntity($sql, [], $entity::getClassName());
        } catch (TypeError $e) {
            $this->assertInstanceOf(TypeError::class, $e);
        }
    }

    /**
     * @covers ::__construct
     * @covers ::executeStatement
     * @covers ::getEntity
     * @covers ::getInstance
     * @covers ::getPreparedStatement
     * @covers ::getIdForInsert
     * @covers ::hasPDO
     * @covers ::resetInstance
     */
    public function testCanGetIdForInsert(): void
    {
        $faker = Factory::create();
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);
        $pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(1);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        $sql = "INSERT" . $faker->sentence();
        $unit->getIdForInsert($sql, []);
    }

    /**
     * @covers ::__construct
     * @covers ::getIdForInsert
     * @covers ::getInstance
     * @covers ::hasPDO
     * @covers ::resetInstance
     */
    public function testWillThrowExceptionForNonInsertSQL():void
    {
        $faker = Factory::create();
        $pdo = $this->createMock(PDO::class);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        $sql = $faker->sentence();
        $this->expectException(\InvalidArgumentException::class);
        $unit->getIdForInsert($sql, []);
    }

    /**
     * @covers ::__construct
     * @covers ::getIdForInsert
     * @covers ::getInstance
     * @covers ::getPreparedStatement
     * @covers ::handleError
     * @covers ::hasPDO
     * @covers ::resetInstance
     */
    public function testWillCatchThrowableForFailedInsertQuery(): void
    {
        $faker = Factory::create();
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn(false);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        $sql = "INSERT" . $faker->sentence();
        $actual = $unit->getIdForInsert($sql, []);
        $this->assertEquals(0, $actual);
    }


    /**
     * @covers ::__construct
     * @covers ::executeStatement
     * @covers ::getEntity
     * @covers ::getInstance
     * @covers ::getPreparedStatement
     * @covers ::getRowsAffectedForUpdate
     * @covers ::hasPDO
     * @covers ::resetInstance
     */
    public function testCanGetRowsAffectedForUpdate(): void
    {
        $faker = Factory::create();
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->Once())
            ->method('rowCount')
            ->willReturn(1);
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        $sql = "UPDATE" . $faker->sentence();
        $unit->getRowsAffectedForUpdate($sql, []);
    }

    /**
     * @covers ::__construct
     * @covers ::getInstance
     * @covers ::getRowsAffectedForUpdate
     * @covers ::hasPDO
     * @covers ::resetInstance
     */
    public function testWillThrowExceptionForNonUpdateQuery(): void
    {
        $faker = Factory::create();
        $pdo = $this->createMock(PDO::class);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        $sql = $faker->sentence();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectException(\InvalidArgumentException::class);
        $unit->getRowsAffectedForUpdate($sql, []);
    }

    /**
     * @covers ::__construct
     * @covers ::getInstance
     * @covers ::getPreparedStatement
     * @covers ::getRowsAffectedForUpdate
     * @covers ::handleError
     * @covers ::hasPDO
     * @covers ::resetInstance
     */
    public function testWillCatchThrowableForFailedUpdateQuery(): void
    {
        $faker = Factory::create();
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn(false);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        $sql = "UPDATE" . $faker->sentence();
        $actual = $unit->getRowsAffectedForUpdate($sql, []);
        $this->assertEquals(0, $actual);
    }

    /**
     * @covers ::__construct
     * @covers ::execute
     * @covers ::executeStatement
     * @covers ::getEntity
     * @covers ::getInstance
     * @covers ::getPreparedStatement
     * @covers ::hasPDO
     * @covers ::resetInstance
     */
    public function testCanExecute(): void
    {
        $faker = Factory::create();
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        $sql = $faker->sentence();
        $this->assertTrue($unit->execute($sql, []));
    }

    /**
     * @covers ::__construct
     * @covers ::execute
     * @covers ::getErrors
     * @covers ::getInstance
     * @covers ::getPreparedStatement
     * @covers ::handleError
     * @covers ::hasPDO
     * @covers ::resetInstance
     */
    public function testWillCatchThrowableOnFailedExecute(): void
    {
        $faker = Factory::create();
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn(false);
        Database::resetInstance();
        $unit = Database::getInstance($pdo);
        $sql = $faker->sentence();
        $this->assertFalse($unit->execute($sql, []));
        $this->assertNotEmpty($unit->getErrors());
    }
}
