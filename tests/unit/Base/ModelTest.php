<?php

namespace Subtext\AppEngine\Base;

use PHPUnit\Framework\TestCase;
use Subtext\AppEngine\Services\Database;

/**
 * Class ModelTest
 *
 * @package Subtext\AppEngine\Base
 * @coversDefaultClass \Subtext\AppEngine\Base\Model
 */
class ModelTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testCanCreateModel(): void
    {
        $db = $this->createMock(Database::class);
        $unit = new class ($db) extends Model {
            public function getData(): array
            {
                return [];
            }
        };
        $actual = $unit->getData();
        $expected = [];
        $this->assertEquals($actual, $expected);
    }
}