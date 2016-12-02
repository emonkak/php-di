<?php

namespace Emonkak\Di\Tests\Dependency;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Dependency\SingletonFactoryDependency;
use Emonkak\Di\Tests\Fixtures\Lambda;

/**
 * @covers Emonkak\Di\Dependency\SingletonFactoryDependency
 */
class SingletonFactoryDependencyTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateBy()
    {
        $expectedValue = new \stdClass();

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->exactly(2))
            ->method('isStored')
            ->with('foo')
            ->will($this->onConsecutiveCalls(false, true));
        $container
            ->expects($this->once())
            ->method('store')
            ->with('foo', $this->identicalTo($expectedValue));
        $container
            ->expects($this->once())
            ->method('get')
            ->with('foo')
            ->willReturn($expectedValue);

        $factory = $this->createMock(Lambda::class, ['__invoke']);
        $factory
            ->expects($this->once())
            ->method('__invoke')
            ->willReturn($expectedValue);

        $dependency = new SingletonFactoryDependency('foo', $factory, []);

        $this->assertSame($expectedValue, $dependency->instantiateBy($container));
        $this->assertSame($expectedValue, $dependency->instantiateBy($container));
    }

    public function testIsSingleton()
    {
        $dependency = new SingletonFactoryDependency('foo', function() {}, []);

        $this->assertTrue($dependency->isSingleton());
    }
}
