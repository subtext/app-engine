<?php
namespace Subtext\AppEngine\Test\Unit;

use PHPUnit\Framework\TestCase;
use Subtext\AppEngine\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ControllerTest
 *
 * @package Subtext\AppEngine\Base
 * @coversDefaultClass \Subtext\AppEngine\Controller
 */
class ControllerTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::execute
     */
    public function testCanExecute(): void
    {
        $expected = 'Hello World';
        $unit     = new class() extends Controller {
            public function execute(mixed $params): Response
            {
                return new Response('Hello World');
            }
        };
        $actual = $unit->execute([]);
        $this->assertEquals($actual->getContent(), $expected);
    }
}
