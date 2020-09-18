<?php
namespace Subtext\AppFactory;

use PHPUnit\Framework\TestCase;

/**
 * Class FallbackTest
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
class FallbackTest extends TestCase
{
    public function testFailGracefully(): void
    {
        $expected = 'Goodbye cruel world!';
        $throwable = new \Exception($expected);
        $fallback = new Fallback($throwable);
        ob_start();
        $fallback->failGracefully();
        $actual = ob_get_clean();
        $this->assertEquals($expected, $actual);
    }
}
