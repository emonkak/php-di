<?php

namespace Emonkak\Di\Tests\Dependency;

use Emonkak\Di\Container;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\DependencyVisitorInterface;
use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;

/**
 * @covers Emonkak\Di\Dependency\FactoryDependency
 */
class FactoryDependencyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetIterator()
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

        $foo = new FactoryDependency('foo', function() {}, [$bar, $baz]);

        $this->assertEquals(['foo' => $foo, 'bar' => $bar, 'baz' => $baz], iterator_to_array($foo));
    }

    public function testAccept()
    {
        $dependency = new FactoryDependency('foo', function() {}, []);

        $visitor = $this->getMock(DependencyVisitorInterface::class);
        $visitor
            ->expects($this->once())
            ->method('visitFactoryDependency')
            ->willReturn($expectedValue = new \stdClass());

        $this->assertSame($expectedValue, $dependency->accept($visitor));
    }

    public function testDependencies()
    {
        $paramerters = [$this->getMock(DependencyInterface::class)];
        $dependency = new FactoryDependency('foo', function() {}, $paramerters);

        $this->assertSame($paramerters, $dependency->getDependencies());
    }

    public function testKey()
    {
        $dependency = new FactoryDependency('foo', function() {}, []);

        $this->assertSame('foo', $dependency->getKey());
    }

    public function testInstantiateBy()
    {
        $injectionPolicy = new DefaultInjectionPolicy();
        $cache = new \ArrayObject();
        $pool = new \ArrayObject();
        $container = new Container($injectionPolicy, $cache, $pool);

        $parameter1 = $this->getMock(DependencyInterface::class);
        $parameter1
            ->expects($this->once())
            ->method('instantiateBy')
            ->with($this->identicalTo($container), $this->identicalTo($pool))
            ->willReturn($parameter1Value = new \stdClass());

        $parameter2 = $this->getMock(DependencyInterface::class);
        $parameter2
            ->expects($this->once())
            ->method('instantiateBy')
            ->with($this->identicalTo($container), $this->identicalTo($pool))
            ->willReturn($parameter2Value = new \stdClass());

        $factory = $this->getMock('stdClass', ['__invoke']);
        $factory
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo($parameter1Value), $this->identicalTo($parameter2Value))
            ->willReturn($expectedValue = new \stdClass());

        $dependency = new FactoryDependency('foo', $factory, [$parameter1, $parameter2]);

        $this->assertSame($expectedValue, $dependency->instantiateBy($container, $pool));
    }

    public function testIsSingleton()
    {
        $dependency = new FactoryDependency('foo', function() {}, []);

        $this->assertFalse($dependency->isSingleton());
    }

    public function testGetFactory()
    {
        $factory = function() {};
        $dependency = new FactoryDependency('foo', $factory, []);

        $this->assertSame($factory, $dependency->getFactory());
    }

    public function testGetParameters()
    {
        $paramerters = [$this->getMock(DependencyInterface::class)];
        $dependency = new FactoryDependency('foo', function() {}, $paramerters);

        $this->assertSame($paramerters, $dependency->getParameters());
    }

    public function testAsSingleton()
    {
        $original = new FactoryDependency(
            'foo',
            function() {},
            [$this->getMock(DependencyInterface::class)]
        );
        $singleton = $original->asSingleton();

        $this->assertSame($original->getKey(), $singleton->getKey());
        $this->assertSame($original->getFactory(), $singleton->getFactory());
        $this->assertSame($original->getParameters(), $singleton->getParameters());
        $this->assertTrue($singleton->isSingleton());
    }
}
