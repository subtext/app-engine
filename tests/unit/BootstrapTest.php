<?php
namespace Subtext\AppEngine;

use Exception;
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
     * @covers \Subtext\AppEngine\Application::__construct
     */
    public function testGetApplication()
    {
        $rootPath = dirname(__DIR__, 2);
        $bootstrap = new Bootstrap($rootPath);
        $app = $bootstrap->getApplication();
        $this->assertInstanceOf(Application::class, $app);
    }
}
