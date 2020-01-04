<?php
namespace Subtext\AppFactory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Class BootstrapTest
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2019 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 * @coversDefaultClass \Subtext\AppFactory\Bootstrap
 */
class BootstrapTest extends TestCase
{

    /**
     * @throws \Exception
     * @covers ::__construct
     * @covers ::getApplication
     */
    public function testGetApplication()
    {
        $rootPath = dirname(__DIR__, 2);
        $bootstrap = new Bootstrap($rootPath);
        $app = $bootstrap->getApplication();
        $this->assertInstanceOf(Application::class, $app);
    }

    /**
     * @throws \Exception
     * @covers ::_construct
     * @covers ::getContainer
     */
    public function testGetContainer()
    {
        $rootPath = realpath('../..');
        $bootstrap = new Bootstrap($rootPath);
        $container = $bootstrap->getContainer();
        $this->assertInstanceOf(ContainerInterface::class, $container);
    }
}
