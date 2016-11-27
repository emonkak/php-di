<?php

namespace Emonkak\Di\Tests\Dependency;

use Emonkak\Di\Dependency\SingletonFactoryDependency;
use Emonkak\Di\Tests\Fixtures\Lambda;
use Interop\Container\ContainerInterface;

/**
 * @covers Emonkak\Di\Dependency\SingletonFactoryDependency
 */
class SingletonFactoryDependencyTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateBy()
    {
        $container = $this->createMock(ContainerInterface::class);
        $pool = new \ArrayObject();

        $expectedValue = new \stdClass();

        $factory = $this->createMock(Lambda::class, ['__invoke']);
        $factory
            ->expects($this->once())
            ->method('__invoke')
            ->willReturn($expectedValue);

        $dependency = new SingletonFactoryDependency('foo', $factory, []);

        $this->assertSame($expectedValue, $dependency->instantiateBy($container, $pool));
        $this->assertSame($expectedValue, $dependency->instantiateBy($container, $pool));
    }

    public function testIsSingleton()
    {
        $dependency = new SingletonFactoryDependency('foo', function() {}, []);

        $this->assertTrue($dependency->isSingleton());
    }
}
