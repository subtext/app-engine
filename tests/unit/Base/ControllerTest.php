<?php
namespace Subtext\AppEngine\Base;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ControllerTest
 *
 * @package Subtext\AppEngine\Base
 * @coversDefaultClass \Subtext\AppEngine\Base\Controller
 */
class ControllerTest extends TestCase
{
    /**
     * @covers ::execute
     */
    public function testCanExecute(): void
    {
        $expected = 'Hello World';
        $response = new Response($expected);
        $model = $this->createMock(Model::class);
        $model->expects($this->once())->method('getData')->willReturn([]);
        $view = $this->createMock(View::class);
        $view->expects($this->once())->method('display')->with([])->willReturn($response);
        $unit = new class($model, $view) extends Controller {
            public function __construct($m, $v) {
                $this->model = $m;
                $this->view = $v;
            }
        };
        $actual = $unit->execute();
        $this->assertEquals($expected, $actual->getContent());
    }
}