<?php

namespace Emonkak\Di\Tests;

use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Tests\Stubs\FooBundle;

abstract class AbstractContrainerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->container = $this->prepareContainer();
    }

    public function testConfigure()
    {
        $configurator = $this->getMock('Emonkak\Di\ContainerConfiguratorInterface');
        $configurator
            ->expects($this->once())
            ->method('configure')
            ->with($this->identicalTo($this->container));

        $this->container->configure($configurator);
    }

    public function testResolve()
    {
        $this->container->configure(new FooBundle());

        $fooDependency = $this->container->resolve('Emonkak\Di\Tests\Stubs\Foo');

        $this->assertInstanceOf('Emonkak\Di\Dependency\ObjectDependency', $fooDependency);

        return $fooDependency;
    }

    /**
     * @depends testResolve
     */
    public function testMaterialize(DependencyInterface $fooDependency)
    {
        $this->container->configure(new FooBundle());

        $foo = $this->container->materialize($fooDependency);

        $this->assertInstanceOf('Emonkak\Di\Tests\Stubs\Foo', $foo);
        $this->assertInstanceOf('Emonkak\Di\Tests\Stubs\Bar', $foo->bar);
        $this->assertInstanceOf('Emonkak\Di\Tests\Stubs\Baz', $foo->bar->baz);
        $this->assertInstanceOf('Emonkak\Di\Tests\Stubs\Baz', $foo->baz);
        $this->assertInstanceOf('Closure', $foo->hoge);
        $this->assertSame($foo->baz, $foo->bar->baz);
        $this->assertSame('payo', $foo->baz->piyo);
        $this->assertSame('payo', $foo->baz->payo);
        $this->assertSame('poyo', $foo->baz->poyo);
    }

    /**
     * @dataProvider provideResolveThrowsNotFoundException
     *
     * @expectedException Interop\Container\Exception\NotFoundException
     */
    public function testResolveThrowsNotFoundException($key)
    {
        $this->container->resolve($key);
    }

    public function provideResolveThrowsNotFoundException()
    {
        return [
            ['Emonkak\Di\Tests\Stubs\Foo'],
            ['Emonkak\Di\Tests\Stubs\Qux']
        ];
    }

    public function testGet()
    {
        $this->container->configure(new FooBundle());

        $foo = $this->container->get('Emonkak\Di\Tests\Stubs\Foo');

        $this->assertInstanceOf('Emonkak\Di\Tests\Stubs\Foo', $foo);
        $this->assertInstanceOf('Emonkak\Di\Tests\Stubs\Bar', $foo->bar);
        $this->assertInstanceOf('Emonkak\Di\Tests\Stubs\Baz', $foo->bar->baz);
        $this->assertInstanceOf('Emonkak\Di\Tests\Stubs\Baz', $foo->baz);
        $this->assertSame($foo->baz, $foo->bar->baz);
        $this->assertSame('payo', $foo->baz->piyo);
        $this->assertSame('payo', $foo->baz->payo);
        $this->assertSame('poyo', $foo->baz->poyo);

        $this->assertInstanceOf('stdClass', $this->container->get('stdClass'));
    }

    public function testHas()
    {
        $this->container->configure(new FooBundle());

        $this->assertTrue($this->container->has('Emonkak\Di\Tests\Stubs\Foo'));
        $this->assertFalse($this->container->has('Emonkak\Di\Tests\Stubs\FooInterface'));
    }

    abstract protected function prepareContainer();
}
