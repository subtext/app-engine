<?php

namespace Subtext\AppEngine\Test\Unit\Controllers;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Subtext\AppEngine\Controller;
use Subtext\AppEngine\Controllers\Factory;
use Symfony\Component\HttpFoundation\Response;

class FactoryTest extends TestCase
{
    public function testCanPassControllerThroughClosure(): void
    {
        $unit = new Factory(function(string $class) {
            return new class () extends Controller {
                public function execute(mixed $params): Response
                {
                    return new Response();
                }
            };
        });
        $this->assertInstanceOf(Controller::class, $unit->get(''));
    }

    public function testWillThrowRuntimeException(): void
    {
        $unit = new Factory(function(string $class) {
            return null;
        });
        $this->expectException(RuntimeException::class);
        $unit->get('foobar');
    }
}
