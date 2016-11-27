<?php

namespace Emonkak\Di\Tests\Dependency;

use Emonkak\Di\Container;
use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\Dependency\SingletonFactoryDependency;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;

/**
 * @covers Emonkak\Di\Dependency\SingletonFactoryDependency
 */
class SingletonFactoryDependencyTest extends \PHPUnit_Framework_TestCase
{
    public function testMaterializeBy()
    {
        $injectionPolicy = new DefaultInjectionPolicy();
        $cache = new \ArrayObject();
        $pool = new \ArrayObject();
        $container = new Container($injectionPolicy, $cache, $pool);

        $factory = $this->getMock(\stdClass::class, ['__invoke']);
        $factory
            ->expects($this->once())
            ->method('__invoke')
            ->willReturn($expectedValue = new \stdClass());

        $dependency = new SingletonFactoryDependency('foo', $factory, []);

        $this->assertSame($expectedValue, $dependency->materializeBy($container, $pool));
        $this->assertSame($expectedValue, $dependency->materializeBy($container, $pool));
    }

    public function testIsSingleton()
    {
        $dependency = new SingletonFactoryDependency('foo', function() {}, []);

        $this->assertTrue($dependency->isSingleton());
    }
}
