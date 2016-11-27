<?php

namespace Emonkak\Di\Tests\Dependency;

use Interop\Container\ContainerInterface;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\DependencyVisitorInterface;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Tests\Fixtures\Bar;
use Emonkak\Di\Tests\Fixtures\Baz;
use Emonkak\Di\Tests\Fixtures\Foo;

/**
 * @covers Emonkak\Di\Dependency\ObjectDependency
 */
class ObjectDependencyTest extends \PHPUnit_Framework_TestCase
{
    public function testTraverse()
    {
        $foo = $this->getMock(DependencyInterface::class);
        $foo
            ->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator(['foo' => $foo]));
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

        $dependency = new ObjectDependency(
            'service',
            \stdClass::class,
            [$foo],
            ['setBar' => [$bar]],
            ['baz' => $baz]
        );

        $this->assertEquals(['service' => $dependency, 'foo' => $foo, 'bar' => $bar, 'baz' => $baz], iterator_to_array($dependency));
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
        $fooDependency = new ObjectDependency(
            'foo',
            Foo::class,
            [],
            [],
            []
        );
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

        $dependency = new ObjectDependency(
            'service',
            ObjectDependencyTestService::class,
            [$fooDependency],
            ['setBar' => [$barDependency]],
            ['baz' => $bazDependency]
        );

        $this->assertSame([$fooDependency, $barDependency, $bazDependency], $dependency->getDependencies());
    }

    public function testKey()
    {
        $dependency = new ObjectDependency('service', ObjectDependencyTestService::class, [], [], []);

        $this->assertSame('service', $dependency->getKey());
    }

    public function testInstantiateBy()
    {
        $container = $this->getMock(ContainerInterface::class);
        $pool = new \ArrayObject();

        $fooDependency = new ObjectDependency(
            'foo',
            Foo::class,
            [],
            [],
            []
        );
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

        $dependency = new ObjectDependency(
            'service',
            ObjectDependencyTestService::class,
            [$fooDependency],
            ['setBar' => [$barDependency]],
            ['baz' => $bazDependency]
        );

        $service = $dependency->instantiateBy($container, $pool);

        $this->assertInstanceOf(ObjectDependencyTestService::class, $service);
        $this->assertInstanceOf(Foo::class, $service->foo);
        $this->assertInstanceOf(Bar::class, $service->bar);
        $this->assertInstanceOf(Baz::class, $service->baz);
    }

    public function testIsSingleton()
    {
        $dependency = new ObjectDependency('service', ObjectDependencyTestService::class, [], [], []);

        $this->assertFalse($dependency->isSingleton());
    }

    public function testGetClassName()
    {
        $dependency = new ObjectDependency('service', ObjectDependencyTestService::class, [], [], []);

        $this->assertSame(ObjectDependencyTestService::class, $dependency->getClassName());
    }

    public function testGetConstructorParameters()
    {
        $paramerters = [$this->getMock(DependencyInterface::class)];
        $dependency = new ObjectDependency('service', ObjectDependencyTestService::class, $paramerters, [], []);

        $this->assertSame($paramerters, $dependency->getConstructorDependencies());
    }

    public function testGetMethodDependencies()
    {
        $methodDependencies = ['setFoo' => $this->getMock(DependencyInterface::class)];
        $dependency = new ObjectDependency('service', ObjectDependencyTestService::class, [], $methodDependencies, []);

        $this->assertSame($methodDependencies, $dependency->getMethodDependencies());
    }

    public function testGetPropertyInjections()
    {
        $propertyDependencies = ['baz' => $this->getMock(DependencyInterface::class)];
        $dependency = new ObjectDependency('service', ObjectDependencyTestService::class, [], [], $propertyDependencies);

        $this->assertSame($propertyDependencies, $dependency->getPropertyDependencies());
    }

    public function testAsSingleton()
    {
        $original = new ObjectDependency(
            'service',
            ObjectDependencyTestService::class,
            [$this->getMock(DependencyInterface::class)],
            ['setBar' => $this->getMock(DependencyInterface::class)],
            ['baz' => $this->getMock(DependencyInterface::class)]
        );
        $singleton = $original->asSingleton();

        $this->assertSame($original->getKey(), $singleton->getKey());
        $this->assertSame($original->getClassName(), $singleton->getClassName());
        $this->assertSame($original->getMethodDependencies(), $singleton->getMethodDependencies());
        $this->assertSame($original->getPropertyDependencies(), $singleton->getPropertyDependencies());
        $this->assertSame($original->getPropertyDependencies(), $singleton->getPropertyDependencies());
    }
}

class ObjectDependencyTestService
{
    public $foo;

    public $bar;

    public $baz;

    public function __construct(Foo $foo)
    {
        $this->foo = $foo;
    }

    public function setBar(Bar $bar)
    {
        $this->bar = $bar;
    }
}
