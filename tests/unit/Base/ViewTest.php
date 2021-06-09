<?php
namespace Subtext\AppFactory\Base;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * @coversDefaultClass \Subtext\AppFactory\Base\View
 */
class ViewTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::display
     */
    public function testThatDisplayReturnsAResponse(): void
    {
        $twig = $this->createMock(Environment::class);
        $unit = new class($twig) extends View {
            public function display(array $data): Response
            {
                return new Response('Hello World');
            }
        };
        $actual = $unit->display([]);
        $this->assertInstanceOf(Response::class, $actual);
    }
}