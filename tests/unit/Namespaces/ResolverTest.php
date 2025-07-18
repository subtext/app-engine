<?php

namespace Subtext\AppEngine\Test\Unit\Namespaces;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use Subtext\AppEngine\Namespaces\Resolver;

class ResolverTest extends TestCase
{

    public function setUp(): void
    {
        vfsStream::setup('root', null,[
            'var' => [
                'www' => [
                    'src' => [
                        'Alpha.php' => 'alpha',
                        'Beta.php' => 'beta',
                        'Gamma.php' => 'gamma',
                        'Colors' => [
                            'Red.php' => 'red',
                            'Green.php' => 'green',
                            'Blue.php' => 'blue',
                        ],
                    ],
                ],
            ],
        ]);
        parent::setUp();
    }

    public function testCanResolveClassesFromNamespaces(): void
    {
        $unit = new Resolver('Subtext\\FooBar\\', vfsStream::url('root/var/www/src'));
        $actual = $unit->getClassesFrom('Subtext\\FooBar\\');
        $this->assertEquals('Subtext\\FooBar\\Alpha', $actual[0]);
        $this->assertEquals('Subtext\\FooBar\\Beta', $actual[1]);
        $this->assertEquals('Subtext\\FooBar\\Gamma', $actual[2]);
    }

    public function testCanResolveClassesRecursively(): void
    {
        $unit = new Resolver('Subtext\\FooBar\\', vfsStream::url('root/var/www/src'));
        $actual = $unit->getClassesFrom('Subtext\\FooBar\\', true);
        $this->assertEquals('Subtext\\FooBar\\Colors\\Red', $actual[3]);
        $this->assertEquals('Subtext\\FooBar\\Colors\\Green', $actual[4]);
        $this->assertEquals('Subtext\\FooBar\\Colors\\Blue', $actual[5]);
    }

    public function testWillEnforceRootNamespace(): void
    {
        $unit = new Resolver('Subtext\\FooBar\\', vfsStream::url('root/var'));
        $this->expectException(InvalidArgumentException::class);
        $unit->getClassesFrom('Microsoft\\Teams\\', true);
    }

    public function testWillEnforceNamespaceTrailingSlash(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $unit = new Resolver('Subtext\\FooBar', vfsStream::url('root/var'));
    }

    public function testWillValidateNamespaceRootDirectory(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $unit = new Resolver('Subtext\\FooBar\\', vfsStream::url('foobar'));
    }

    public function testWillThrowExceptionForInvalidPsr4Namespace(): void
    {
        $unit = new Resolver('Subtext\\FooBar\\', vfsStream::url('root/var/www/src/Colors'));
        $this->expectException(InvalidArgumentException::class);
        $unit->getClassesFrom('Subtext\\FooBar\\Colors\\');
    }
}
