<?php

namespace Emonkak\Di\Tests\Dependency;

use Emonkak\Di\Container;
use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;

class FactoryDependencyTest extends \PHPUnit_Framework_TestCase
{
    public function testAccept()
    {
        $dependency = new FactoryDependency('foo', function() {}, []);

        $visitor = $this->getMock('Emonkak\Di\Dependency\DependencyVisitorInterface');
        $visitor
            ->expects($this->once())
            ->method('visitFactoryDependency')
            ->willReturn($expectedValue = new \stdClass());

        $this->assertSame($expectedValue, $dependency->accept($visitor));
    }

    public function testDependencies()
    {
        $paramerters = [$this->getMock('Emonkak\Di\Dependency\DependencyInterface')];
        $dependency = new FactoryDependency('foo', function() {}, $paramerters);

        $this->assertSame($paramerters, $dependency->getDependencies());
    }

    public function testKey()
    {
        $dependency = new FactoryDependency('foo', function() {}, []);

        $this->assertSame('foo', $dependency->getKey());
    }

    public function testMaterializeBy()
    {
        $injectionPolicy = new DefaultInjectionPolicy();
        $cache = new \ArrayObject();
        $pool = new \ArrayObject();
        $container = new Container($injectionPolicy, $cache, $pool);

        $parameter1 = $this->getMock('Emonkak\Di\Dependency\DependencyInterface');
        $parameter1
            ->expects($this->once())
            ->method('materializeBy')
            ->with($this->identicalTo($container), $this->identicalTo($pool))
            ->willReturn($parameter1Value = new \stdClass());

        $parameter2 = $this->getMock('Emonkak\Di\Dependency\DependencyInterface');
        $parameter2
            ->expects($this->once())
            ->method('materializeBy')
            ->with($this->identicalTo($container), $this->identicalTo($pool))
            ->willReturn($parameter2Value = new \stdClass());

        $factory = $this->getMock('stdClass', ['__invoke']);
        $factory
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo($parameter1Value), $this->identicalTo($parameter2Value))
            ->willReturn($expectedValue = new \stdClass());

        $dependency = new FactoryDependency('foo', $factory, [$parameter1, $parameter2]);

        $this->assertSame($expectedValue, $dependency->materializeBy($container, $pool));
    }

    public function testIsSingleton()
    {
        $dependency = new FactoryDependency('foo', function() {}, []);

        $this->assertFalse($dependency->isSingleton());
    }

    public function testTraverse()
    {
        $callback = $this->getMock('stdClass', ['__invoke']);

        $parameter1 = $this->getMock('Emonkak\Di\Dependency\DependencyInterface');
        $parameter1
            ->expects($this->once())
            ->method('traverse')
            ->with($this->identicalTo($callback));
        $parameter2 = $this->getMock('Emonkak\Di\Dependency\DependencyInterface');
        $parameter2
            ->expects($this->once())
            ->method('traverse')
            ->with($this->identicalTo($callback));

        $dependency = new FactoryDependency('foo', $callback, [$parameter1, $parameter2]);

        $callback
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo($dependency), 'foo');

        $dependency->traverse($callback);
    }

    public function testGetFactory()
    {
        $factory = function() {};
        $dependency = new FactoryDependency('foo', $factory, []);

        $this->assertSame($factory, $dependency->getFactory());
    }

    public function testGetParameters()
    {
        $paramerters = [$this->getMock('Emonkak\Di\Dependency\DependencyInterface')];
        $dependency = new FactoryDependency('foo', function() {}, $paramerters);

        $this->assertSame($paramerters, $dependency->getParameters());
    }
}
