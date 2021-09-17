<?php
namespace Subtext\AppEngine\Base;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @coversDefaultClass \Subtext\AppEngine\Base\View
 */
class ViewTest extends TestCase
{
    /**
     * @covers ::display
     */
    public function testThatDisplayReturnsAResponse(): void
    {
        $unit = new class() extends View {
            public function display(array $data): Response
            {
                return new Response('Hello World');
            }
        };
        $actual = $unit->display([]);
        $this->assertInstanceOf(Response::class, $actual);
    }
}