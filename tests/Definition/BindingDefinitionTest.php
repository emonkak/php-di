<?php

namespace Emonkak\Di\Tests\Definition;

use Emonkak\Di\Container;
use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\Tests\Definition\Stubs\Bar;
use Emonkak\Di\Tests\Definition\Stubs\Baz;
use Emonkak\Di\Tests\Definition\Stubs\Foo;
use Emonkak\Di\Tests\Definition\Stubs\Qux;

/**
 * @covers Emonkak\Di\Definition\BindingDefinition
 */
class BindingDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveBy()
    {
        $definition = new BindingDefinition('Emonkak\Di\Tests\Definition\Stubs\FooInterface');

        $injectionPolicy = new DefaultInjectionPolicy();
        $container = Container::create($injectionPolicy);
        $barDefinition = $container->bind('Emonkak\Di\Tests\Definition\Stubs\Bar');
        $bazDefinition = $container->set('Emonkak\Di\Tests\Definition\Stubs\Baz', new Baz());
        $quxDefinition = $container->factory('Emonkak\Di\Tests\Definition\Stubs\Qux', function() {
            return new Qux();
        });
        $fooDependency = $definition
            ->to('Emonkak\Di\Tests\Definition\Stubs\Foo')
            ->with([$barDefinition])
            ->withMethod('setBaz', [$bazDefinition])
            ->withProperty('qux', $quxDefinition)
            ->resolveBy($container, $injectionPolicy);

        $this->assertInstanceOf('Emonkak\Di\Dependency\ObjectDependency', $fooDependency);
        $this->assertSame('Emonkak\Di\Tests\Definition\Stubs\FooInterface', $fooDependency->getKey());
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

        $definition = new BindingDefinition('Emonkak\Di\Tests\Definition\Stubs\FooInterface');
        $definition->resolveBy($container, $injectionPolicy);
    }
}

