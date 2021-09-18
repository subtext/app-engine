<?php
namespace Subtext\AppEngine;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Class BootstrapTest
 *
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 * @coversDefaultClass \Subtext\AppEngine\Bootstrap
 */
class BootstrapTest extends TestCase
{
    /**
     * @throws Exception
     * @covers ::__construct
     * @covers ::getContainer
     * @covers ::resolveProjectLocation
     */
    public function testGetContainer()
    {
        $rootPath = dirname(__DIR__, 2);
        $bootstrap = new Bootstrap($rootPath);
        $container = $bootstrap->getContainer();
        $this->assertInstanceOf(ContainerInterface::class, $container);
    }

    /**
     * @throws Exception
     * @covers ::__construct
     * @covers ::getContainer
     * @covers ::getApplication
     * @covers ::resolveProjectLocation
     * @covers \Subtext\AppEngine\Application::__construct
     */
    public function testGetApplication()
    {
        $rootPath = dirname(__DIR__, 2);
        $bootstrap = new Bootstrap($rootPath);
        $app = $bootstrap->getApplication();
        $this->expectException(\InvalidArgumentException::class);
        $bootstrap = new Bootstrap('');
        $this->assertInstanceOf(Application::class, $app);
    }

    /**
     * @covers ::__construct
     * @covers ::resolveProjectLocation
     */
    public function testWillThrowExceptionForBadPath(): void
    {
        $roothPath = __DIR__;
        $this->expectException(InvalidArgumentException::class);
        new Bootstrap($roothPath);
    }
}
