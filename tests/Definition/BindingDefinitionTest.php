<?php

namespace Emonkak\Di\Tests\Definition;

use Emonkak\Di\Container;
use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\Tests\Definition\Stubs\Bar;
use Emonkak\Di\Tests\Definition\Stubs\Baz;
use Emonkak\Di\Tests\Definition\Stubs\Foo;
use Emonkak\Di\Tests\Definition\Stubs\FooInterface;
use Emonkak\Di\Tests\Definition\Stubs\Qux;

/**
 * @covers Emonkak\Di\Definition\BindingDefinition
 */
class BindingDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveBy()
    {
        $definition = new BindingDefinition(FooInterface::class);

        $injectionPolicy = new DefaultInjectionPolicy();
        $container = Container::create($injectionPolicy);
        $barDefinition = $container->bind(Bar::class);
        $bazDefinition = $container->set(Baz::class, new Baz());
        $quxDefinition = $container->factory(Qux::class, function() {
            return new Qux();
        });
        $fooDependency = $definition
            ->to(Foo::class)
            ->with([$barDefinition])
            ->withMethod('setBaz', [$bazDefinition])
            ->withProperty('qux', $quxDefinition)
            ->resolveBy($container, $injectionPolicy);

        $this->assertInstanceOf(ObjectDependency::class, $fooDependency);
        $this->assertSame(FooInterface::class, $fooDependency->getKey());
        $this->assertEquals([$barDefinition->resolveBy($container, $injectionPolicy)], $fooDependency->getConstructorDependencies());
        $this->assertEquals(['setBaz' => [$bazDefinition->resolveBy($container, $injectionPolicy)]], $fooDependency->getMethodDependencies());
        $this->assertEquals(['qux' => $quxDefinition->resolveBy($container, $injectionPolicy)], $fooDependency->getPropertyDependencies());
    }

    /**
     * @expectedException LogicException
     */
    public function testResolveByThrowsLogicException()
    {
        $injectionPolicy = new DefaultInjectionPolicy();
        $container = Container::create($injectionPolicy);

        $definition = new BindingDefinition(FooInterface::class);
        $definition->resolveBy($container, $injectionPolicy);
    }
}
