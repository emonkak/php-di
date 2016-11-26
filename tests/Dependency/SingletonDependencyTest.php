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
    public function testFrom()
    {
        $original = new ObjectDependency(
            'foo',
            'stdClass',
            [$this->getMock('Emonkak\Di\Dependency\DependencyInterface')],
            ['setBaz' => $this->getMock('Emonkak\Di\Dependency\DependencyInterface')],
            ['qux' => $this->getMock('Emonkak\Di\Dependency\DependencyInterface')]
        );
        $new = SingletonDependency::from($original);

        $this->assertInstanceOf('Emonkak\Di\Dependency\SingletonDependency', $new);
        $this->assertSame($original->getKey(), $new->getKey());
        $this->assertSame($original->getClassName(), $new->getClassName());
        $this->assertSame($original->getMethodDependencies(), $new->getMethodDependencies());
        $this->assertSame($original->getPropertyDependencies(), $new->getPropertyDependencies());
    }

    public function testMaterializeBy()
    {
        $injectionPolicy = new DefaultInjectionPolicy();
        $cache = new \ArrayObject();
        $pool = new \ArrayObject();
        $container = new Container($injectionPolicy, $cache, $pool);

        $dependency = new SingletonDependency(
            'stdClass',
            'stdClass',
            [], [], []
        );

        $obj = $dependency->materializeBy($container, $pool);

        $this->assertSame($obj, $dependency->materializeBy($container, $pool));
    }

    public function testIsSingleton()
    {
        $dependency = new SingletonDependency('foo', 'stdClass', [], [], []);

        $this->assertTrue($dependency->isSingleton());
    }
}
