<?php
namespace Subtext\AppEngine;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Class BootstrapTest
 *
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 * @coversDefaultClass \Subtext\AppEngine\Bootstrap
 */
class BootstrapTest extends TestCase
{
    /**
     * @throws \Exception
     * @covers ::__construct
     * @covers ::getContainer
     */
    public function testGetContainer()
    {
        $rootPath = dirname(__DIR__, 2);
        $bootstrap = new Bootstrap($rootPath);
        $container = $bootstrap->getContainer();
        $this->assertInstanceOf(ContainerInterface::class, $container);
        try {

        } catch (\Throwable $e) {
            $this->assertInstanceOf(\InvalidArgumentException::class, $e);
        }
    }

    /**
     * @throws \Exception
     * @covers ::__construct
     * @covers ::getContainer
     * @covers ::getApplication
     * @covers \Subtext\AppEngine\Application::__construct
     */
    public function testGetApplication()
    {
        $bootstrap = new Bootstrap('');
        $app = $bootstrap->getApplication();
        $this->assertInstanceOf(Application::class, $app);
    }
}
