<?php
namespace Subtext\AppEngine\Test\Unit;

use DI\Container;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Subtext\AppEngine\Application;
use Subtext\AppEngine\Controller;
use Subtext\AppEngine\Controllers\Loader;
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
     * @throws Exception
     */
    public function testExecute(): void
    {
        $expected = 'Hello World';
        $response = new Response($expected);
        $controller = $this->createMock(Controller::class);
        $controller->expects($this->once())
            ->method('execute')
            ->with([])
            ->willReturn($response);
        $loader = $this->createMock(Loader::class);
        $loader->expects($this->once())
            ->method('getController')
            ->willReturn($controller);
        $loader->expects($this->once())
            ->method('getParams')
            ->willReturn([]);
        $logger = $this->createMock(LoggerInterface::class);
        $app = new Application($loader, $logger);
        ob_start();
        $app->execute();
        $actual = ob_get_clean();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers ::__construct
     * @covers ::execute
     * @covers ::validateRequestUri
     * @throws Exception
     */
    public function testExecuteWillCatchAnyException(): void
    {
        $loader = $this->createMock(Loader::class);
        $loader->expects($this->once())
            ->method('getController')
            ->willThrowException(new ResourceNotFoundException());
        $logger = $this->createMock(LoggerInterface::class);
        $app = new Application($loader, $logger);
        try {
            $app->execute();
        }catch (Throwable $e) {
            $this->assertInstanceOf(RuntimeException::class, $e);
            $this->assertInstanceOf(ResourceNotFoundException::class, $e->getPrevious());
        }
    }
}
