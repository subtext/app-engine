<?php

namespace Subtext\AppEngine\Test\Unit\Controllers;

use PHPUnit\Framework\TestCase;
use Subtext\AppEngine\Controller;
use Subtext\AppEngine\Controllers\Loader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

class LoaderTest extends TestCase
{
    public function testCanCreateInstance(): void
    {
        $unit = Loader::create(
            new class () extends Controller {
                public function execute(mixed $params): Response
                {
                    return new Response();
                }
            }, []
        );
        $this->assertInstanceOf(Loader::class, $unit);
        $this->assertInstanceOf(Controller::class, $unit->getController());
        $this->assertEquals([], $unit->getParams());
    }

    public function testResolveController(): void
    {
        $expected = ['_controller' => 'Subtext\\AppEngine\\Controllers\\HelloWorld'];
        $request = $this->createMock(Request::class);
        $router = $this->createMock(Router::class);
        $router->expects($this->once())
            ->method('matchRequest')
            ->with($request)
            ->willReturn($expected);
        $actual = Loader::resolveController($router, $request);
        $this->assertEquals($expected, $actual);
    }
}
