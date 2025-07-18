<?php
namespace Subtext\AppEngine;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Subtext\AppEngine\Exceptions\ConfigNotFoundException;

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
        $container = $bootstrap->container;
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
        $app = $bootstrap->application;
        $this->assertInstanceOf(Application::class, $app);
    }

    /**
     * @covers ::__construct
     * @covers ::resolveProjectLocation
     */
    public function testWillThrowExceptionForBadPath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Bootstrap("foobar");
    }

    public function testWillThrowExceptionForUnresolvedConfigDirectory(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Bootstrap(__DIR__);
    }

    public function testWillThrowExceptionForMissingConfigFile(): void
    {
        $unit = new Bootstrap(dirname(__DIR__, 2), 'foobar.php');
        $this->expectException(ConfigNotFoundException::class);
        $unit->container;
    }
}
