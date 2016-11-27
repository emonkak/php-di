<?php

namespace Emonkak\Di\Tests;

use Emonkak\Di\ContainerConfiguratorInterface;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Dependency\ValueDependency;
use Emonkak\Di\Tests\Stubs\Bar;
use Emonkak\Di\Tests\Stubs\Baz;
use Emonkak\Di\Tests\Stubs\Foo;
use Emonkak\Di\Tests\Stubs\FooBundle;
use Emonkak\Di\Tests\Stubs\Optional;

abstract class AbstractContrainerTest extends \PHPUnit_Framework_TestCase
{
    private $container;

    public function setUp()
    {
        $this->container = $this->prepareContainer();
        $this->container->configure(new FooBundle());
    }

    public function testConfigure()
    {
        $configurator = $this->getMock(ContainerConfiguratorInterface::class);
        $configurator
            ->expects($this->once())
            ->method('configure')
            ->with($this->identicalTo($this->container));

        $this->container->configure($configurator);
    }

    public function testResolve()
    {
        $fooDependency = $this->container->resolve(Foo::class);
        $this->assertInstanceOf(ObjectDependency::class, $fooDependency);
    }

    /**
     * @expectedException Interop\Container\Exception\NotFoundException
     */
    public function testResolveThrowsNotFoundException()
    {
        $this->container->resolve(FooInterface::class);
    }

    public function testResolveParameterDependency()
    {
        $optional = new \ReflectionClass(Optional::class);
        $parameters = $optional->getConstructor()->getParameters();

        $fooDependency = $this->container->resolve(Foo::class);
        $nullDependency = new ValueDependency(null);

        $this->assertEquals($fooDependency, $this->container->resolveParameter($parameters[0]));
        $this->assertEquals($nullDependency, $this->container->resolveParameter($parameters[1]));
    }

    public function testResolvePropertyDependency()
    {
        $optional = new \ReflectionClass(Optional::class);

        $fooDependency = $this->container->set('$foo', $this->container->get(Foo::class));
        $nullDependency = new ValueDependency(null);

        $this->assertEquals($fooDependency, $this->container->resolveProperty($optional->getProperty('foo')));
        $this->assertEquals($nullDependency, $this->container->resolveProperty($optional->getProperty('optionalFoo')));
    }

    public function testGet()
    {
        $foo = $this->container->get(Foo::class);
        $this->assertInstanceOf(Foo::class, $foo);
        $this->assertInstanceOf(Bar::class, $foo->bar);
        $this->assertInstanceOf(Baz::class, $foo->bar->baz);
        $this->assertInstanceOf(Baz::class, $foo->baz);
        $this->assertSame($foo->baz, $foo->bar->baz);
        $this->assertSame('payo', $foo->baz->piyo);
        $this->assertSame('payo', $foo->baz->payo);
        $this->assertSame('poyo', $foo->baz->poyo);
    }

    public function testHas()
    {
        $this->assertTrue($this->container->has(Foo::class));
        $this->assertFalse($this->container->has(FooInterface::class));
    }

    public function testMaterialize()
    {
        $fooDependency = $this->container->resolve(Foo::class);
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

    abstract protected function prepareContainer();
}
