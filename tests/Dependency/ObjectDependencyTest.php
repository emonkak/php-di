<?php

namespace Emonkak\Di\Tests\Dependency;

use Emonkak\Di\Container;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\DependencyVisitorInterface;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\Tests\Dependency\Stubs\Bar;
use Emonkak\Di\Tests\Dependency\Stubs\Baz;
use Emonkak\Di\Tests\Dependency\Stubs\Foo;
use Emonkak\Di\Tests\Dependency\Stubs\Qux;

/**
 * @covers Emonkak\Di\Dependency\ObjectDependency
 */
class ObjectDependencyTest extends \PHPUnit_Framework_TestCase
{
    public function testTraverse()
    {
        $bar = $this->getMock(DependencyInterface::class);
        $bar
            ->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator(['bar' => $bar]));
        $baz = $this->getMock(DependencyInterface::class);
        $baz
            ->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator(['baz' => $baz]));
        $qux = $this->getMock(DependencyInterface::class);
        $qux
            ->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator(['qux' => $qux]));

        $foo = new ObjectDependency(
            'foo',
            \stdClass::class,
            [$bar], ['setBaz' => [$baz]], ['qux' => $qux]
        );

        $this->assertEquals(['foo' => $foo, 'bar' => $bar, 'baz' => $baz, 'qux' => $qux], iterator_to_array($foo));
    }

    public function testAccept()
    {
        $dependency = new ObjectDependency('foo', \stdClass::class, [], [], []);

        $visitor = $this->getMock(DependencyVisitorInterface::class);
        $visitor
            ->expects($this->once())
            ->method('visitObjectDependency')
            ->willReturn($expectedValue = new \stdClass());

        $this->assertSame($expectedValue, $dependency->accept($visitor));
    }

    public function testDependencies()
    {
        $container = Container::create();

        $barDependency = new ObjectDependency(
            'bar',
            Bar::class,
            [],
            [],
            []
        );
        $bazDependency = new ObjectDependency(
            'baz',
            Baz::class,
            [],
            [],
            []
        );
        $quxDependency = new ObjectDependency(
            'qux',
            Qux::class,
            [],
            [],
            []
        );
        $fooDependency = new ObjectDependency(
            'foo',
            Foo::class,
            [$barDependency],
            ['setBaz' => [$bazDependency]],
            ['qux' => $quxDependency]
        );

        $this->assertSame([$barDependency, $bazDependency, $quxDependency], $fooDependency->getDependencies());
    }

    public function testKey()
    {
        $dependency = new ObjectDependency('foo', \stdClass::class, [], [], []);

        $this->assertSame('foo', $dependency->getKey());
    }

    public function testInstantiateBy()
    {
        $injectionPolicy = new DefaultInjectionPolicy();
        $cache = new \ArrayObject();
        $pool = new \ArrayObject();
        $container = new Container($injectionPolicy, $cache, $pool);

        $barDependency = new ObjectDependency(
            'bar',
            Bar::class,
            [],
            [],
            []
        );
        $bazDependency = new ObjectDependency(
            'baz',
            Baz::class,
            [],
            [],
            []
        );
        $quxDependency = new ObjectDependency(
            'qux',
            Qux::class,
            [],
            [],
            []
        );
        $fooDependency = new ObjectDependency(
            'foo',
            Foo::class,
            [$barDependency],
            ['setBaz' => [$bazDependency]],
            ['qux' => $quxDependency]
        );

        $foo = $fooDependency->instantiateBy($container, $pool);

        $this->assertInstanceOf(Foo::class, $foo);
        $this->assertInstanceOf(Bar::class, $foo->bar);
        $this->assertInstanceOf(Baz::class, $foo->baz);
        $this->assertInstanceOf(Qux::class, $foo->qux);
    }

    public function testIsSingleton()
    {
        $dependency = new ObjectDependency('foo', \stdClass::class, [], [], []);

        $this->assertFalse($dependency->isSingleton());
    }

    public function testGetClassName()
    {
        $dependency = new ObjectDependency('foo', \stdClass::class, [], [], []);

        $this->assertSame(\stdClass::class, $dependency->getClassName());
    }

    public function testGetConstructorParameters()
    {
        $paramerters = [$this->getMock(DependencyInterface::class)];
        $dependency = new ObjectDependency('foo', \stdClass::class, $paramerters, [], []);

        $this->assertSame($paramerters, $dependency->getConstructorDependencies());
    }

    public function testGetMethodDependencies()
    {
        $methodDependencies = ['setBar' => $this->getMock(DependencyInterface::class)];
        $dependency = new ObjectDependency('foo', \stdClass::class, [], $methodDependencies, []);

        $this->assertSame($methodDependencies, $dependency->getMethodDependencies());
    }

    public function testGetPropertyInjections()
    {
        $propertyDependencies = ['qux' => $this->getMock(DependencyInterface::class)];
        $dependency = new ObjectDependency('foo', \stdClass::class, [], [], $propertyDependencies);

        $this->assertSame($propertyDependencies, $dependency->getPropertyDependencies());
    }

    public function testAsSingleton()
    {
        $original = new ObjectDependency(
            'foo',
            \stdClass::class,
            [$this->getMock(DependencyInterface::class)],
            ['setBaz' => $this->getMock(DependencyInterface::class)],
            ['qux' => $this->getMock(DependencyInterface::class)]
        );
        $singleton = $original->asSingleton();

        $this->assertSame($original->getKey(), $singleton->getKey());
        $this->assertSame($original->getClassName(), $singleton->getClassName());
        $this->assertSame($original->getMethodDependencies(), $singleton->getMethodDependencies());
        $this->assertSame($original->getPropertyDependencies(), $singleton->getPropertyDependencies());
        $this->assertSame($original->getPropertyDependencies(), $singleton->getPropertyDependencies());
    }
}
