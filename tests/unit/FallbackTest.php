<?php
namespace Subtext\AppEngine;

use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class FallbackTest
 *
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */
class FallbackTest extends TestCase
{
    /**
     * @covers \Subtext\AppEngine\Fallback
     */
    public function testFailGracefully(): void
    {
        $expected = 'Goodbye cruel world!';
        $throwable = new Exception($expected, 500);
        $fallback = new Fallback($throwable);
        ob_start();
        $fallback->failGracefully();
        $actual = ob_get_clean();
        $this->assertStringContainsString($expected, $actual);
    }
}
