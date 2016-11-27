<?php

use Emonkak\Di\Dependency\SingletonDependency;
use Interop\Container\ContainerInterface;

/**
 * @covers Emonkak\Di\Dependency\SingletonDependency
 */
class SingletonDependencyTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateBy()
    {
        $container = $this->createMock(ContainerInterface::class);
        $pool = new \ArrayObject();

        $dependency = new SingletonDependency(
            \stdClass::class,
            \stdClass::class,
            [],
            [],
            []
        );

        $obj = $dependency->instantiateBy($container, $pool);
        $this->assertSame($obj, $dependency->instantiateBy($container, $pool));
    }

    public function testIsSingleton()
    {
        $dependency = new SingletonDependency(\stdClass::class, \stdClass::class, [], [], []);

        $this->assertTrue($dependency->isSingleton());
    }
}
