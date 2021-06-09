<?php
namespace Subtext\AppFactory;

use DI\Container;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Subtext\AppFactory\Base\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Throwable;

/**
 * Class ApplicationTest
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 * @coversDefaultClass \Subtext\AppFactory\Application
 */
class ApplicationTest extends TestCase
{
    /**
     * @throws \Exception
     * @covers ::__construct
     * @covers ::execute
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
        $request->expects($this->once())
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
     */
    public function testExecuteWillThrowTrailingSlashException(): void
    {
        $container = $this->createMock(Container::class);
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('getUri')
            ->willReturn('https://example.com/');
        $router = $this->createMock(Router::class);
        $app = new Application($container, $request, $router);
        try {
            $app->execute();
        }catch (Throwable $e) {
            $this->assertInstanceOf(RuntimeException::class, $e);
            $this->assertInstanceOf(\InvalidArgumentException::class, $e->getPrevious());
        }
    }

    /**
     * @covers ::__construct
     * @covers ::execute
     */
    public function testExecuteWillThrowMissingControllerException(): void
    {
        $container = $this->createMock(Container::class);
        $container->expects($this->once())
            ->method('has')
            ->with('UnitController')
            ->willReturn(false);
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
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
