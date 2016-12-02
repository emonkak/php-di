<?php

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Dependency\SingletonDependency;

/**
 * @covers Emonkak\Di\Dependency\SingletonDependency
 */
class SingletonDependencyTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateBy()
    {
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->exactly(2))
            ->method('isStored')
            ->with(\stdClass::class)
            ->will($this->onConsecutiveCalls(false, true));
        $container
            ->expects($this->once())
            ->method('store')
            ->with(\stdClass::class, $this->isInstanceOf(\stdClass::class));
        $container
            ->expects($this->once())
            ->method('get')
            ->with(\stdClass::class)
            ->willReturn(new \stdClass());

        $dependency = new SingletonDependency(
            \stdClass::class,
            \stdClass::class,
            [],
            [],
            []
        );

        $this->assertInstanceof(\stdClass::class, $dependency->instantiateBy($container));
        $this->assertInstanceof(\stdClass::class, $dependency->instantiateBy($container));
    }

    public function testIsSingleton()
    {
        $dependency = new SingletonDependency(\stdClass::class, \stdClass::class, [], [], []);

        $this->assertTrue($dependency->isSingleton());
    }
}
