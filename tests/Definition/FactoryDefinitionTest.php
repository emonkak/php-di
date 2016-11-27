<?php

use Emonkak\Di\Container;
use Emonkak\Di\Definition\FactoryDefinition;
use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\Tests\Definition\Stubs\Bar;
use Emonkak\Di\Tests\Definition\Stubs\Baz;
use Emonkak\Di\Tests\Definition\Stubs\Foo;
use Emonkak\Di\Tests\Definition\Stubs\FooFactory;
use SuperClosure\SerializableClosure;

/**
 * @covers Emonkak\Di\Definition\FactoryDefinition
 */
class FactoryDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveDependency()
    {
        $factory = new FooFactory();
        $definition = new FactoryDefinition(Foo::class, $factory);

        $injectionPolicy = new DefaultInjectionPolicy();
        $container = Container::create($injectionPolicy);
        $bazDefinition = $container->set('$baz', new Baz());
        $barDefinition = $container->factory('$bar', function() {
            return new Bar();
        });
        $fooDependency = $definition
            ->with([$barDefinition, $bazDefinition])
            ->resolveBy($container, $injectionPolicy);

        $this->assertInstanceOf(FactoryDependency::class, $fooDependency);
        $this->assertSame(Foo::class, $fooDependency->getKey());
        $this->assertEquals($factory, $fooDependency->getFactory());
        $this->assertEquals([$barDefinition->resolveBy($container, $injectionPolicy), $bazDefinition->resolveBy($container, $injectionPolicy)], $fooDependency->getParameters());
    }

    public function testResolveByWithClosure()
    {
        $factory = function() {
            return new Foo(new Bar());
        };
        $definition = new FactoryDefinition(Foo::class, $factory);

        $injectionPolicy = new DefaultInjectionPolicy();
        $container = Container::create($injectionPolicy);
        $fooDependency = $definition->resolveBy($container, $injectionPolicy);

        $this->assertInstanceOf(FactoryDependency::class, $fooDependency);
        $this->assertSame(Foo::class, $fooDependency->getKey());
        $this->assertInstanceOf(SerializableClosure::class, $fooDependency->getFactory());
        $this->assertSame((new SerializableClosure($factory))->serialize(), $fooDependency->getFactory()->serialize());
        $this->assertEquals([], $fooDependency->getParameters());
    }
}
