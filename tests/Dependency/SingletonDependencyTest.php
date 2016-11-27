<?php

use Emonkak\Di\Container;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Dependency\SingletonDependency;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\Tests\Dependency\Stubs\Bar;
use Emonkak\Di\Tests\Dependency\Stubs\Baz;
use Emonkak\Di\Tests\Dependency\Stubs\Foo;
use Emonkak\Di\Tests\Dependency\Stubs\Qux;

/**
 * @covers Emonkak\Di\Dependency\SingletonDependency
 */
class SingletonDependencyTest extends \PHPUnit_Framework_TestCase
{
    public function testMaterializeBy()
    {
        $injectionPolicy = new DefaultInjectionPolicy();
        $cache = new \ArrayObject();
        $pool = new \ArrayObject();
        $container = new Container($injectionPolicy, $cache, $pool);

        $dependency = new SingletonDependency(
            \stdClass::class,
            \stdClass::class,
            [], [], []
        );

        $obj = $dependency->materializeBy($container, $pool);

        $this->assertSame($obj, $dependency->materializeBy($container, $pool));
    }

    public function testIsSingleton()
    {
        $dependency = new SingletonDependency('foo', \stdClass::class, [], [], []);

        $this->assertTrue($dependency->isSingleton());
    }
}
