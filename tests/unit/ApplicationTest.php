<?php
namespace Subtext\AppEngine;

use DI\Container;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Subtext\AppEngine\Base\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Throwable;

/**
 * Class ApplicationTest
 *
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 * @coversDefaultClass \Subtext\AppEngine\Application
 */
class ApplicationTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::execute
     * @covers ::validateRequestUri
     */
    public function testExecute(): void
    {
        $expected = 'Hello World';
        $response = new Response($expected);
        $controller = $this->createMock(Controller::class);
        $controller->expects($this->once())
            ->method('execute')
            ->willReturn($response);
        $container = $this->createMock(Container::class);
        $container->expects($this->once())
            ->method('has')
            ->with('UnitController')
            ->willReturn(true);
        $container->expects($this->once())
            ->method('get')
            ->with('UnitController')
            ->willReturn($controller);
        $request = $this->createMock(Request::class);
        $request->expects($this->atLeastOnce())
            ->method('getUri')
            ->willReturn('https://example.com');
        $router = $this->createMock(Router::class);
        $router->expects($this->once())
            ->method('matchRequest')
            ->with($request)
            ->willReturn(['_controller' => 'UnitController']);
        $app = new Application($container, $request, $router);
        ob_start();
        $app->execute();
        $actual = ob_get_clean();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers ::__construct
     * @covers ::execute
     * @covers ::validateRequestUri
     */
    public function testExecuteWillThrowTrailingSlashException(): void
    {
        $container = $this->createMock(Container::class);
        $request = $this->createMock(Request::class);
        $request->expects($this->atLeastOnce())
            ->method('getUri')
            ->willReturn('https://example.com/');
        $router = $this->createMock(Router::class);
        $app = new Application($container, $request, $router);
        try {
            $app->execute();
        }catch (Throwable $e) {
            $this->assertInstanceOf(RuntimeException::class, $e);
            $this->assertInstanceOf(InvalidArgumentException::class, $e->getPrevious());
        }
    }

    /**
     * @covers ::__construct
     * @covers ::execute
     * @covers ::validateRequestUri
     */
    public function testExecuteWillThrowMissingControllerException(): void
    {
        $container = $this->createMock(Container::class);
        $container->expects($this->once())
            ->method('has')
            ->with('UnitController')
            ->willReturn(false);
        $request = $this->createMock(Request::class);
        $request->expects($this->atLeastOnce())
            ->method('getUri')
            ->willReturn('https://example.com');
        $router = $this->createMock(Router::class);
        $router->expects($this->once())
            ->method('matchRequest')
            ->with($request)
            ->willReturn(['_controller' => 'UnitController']);

        $app = new Application($container, $request, $router);
        try {
            $app->execute();
        }catch (Throwable $e) {
            $this->assertInstanceOf(RuntimeException::class, $e);
            $this->assertInstanceOf(ResourceNotFoundException::class, $e->getPrevious());
        }
    }
}
