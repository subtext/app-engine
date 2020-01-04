<?php
namespace Subtext\AppFactory;

use DI\ContainerBuilder;
use HtmlValidator\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApplicationTest
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2019 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 * @coversDefaultClass \Subtext\AppFactory\Application
 */
class ApplicationTest extends TestCase
{
    /**
     * @throws \Exception
     * @covers ::__construct
     * @covers ::execute
     * @covers ::getController
     */
    public function testExecute(): void
    {
        $configFile = dirname(__DIR__, 2) . '/config/unit.php';
        $builder = new ContainerBuilder();
        $builder->addDefinitions($configFile);
        $container = $builder->build();
        $request = new Request();
        $app = new Application($container, $request);
        ob_start();
        $app->execute();
        $output = ob_get_clean();
        $validator = new Validator();
        $result = $validator->validateDocument($output);
        $this->assertFalse($result->hasErrors());
    }
}
