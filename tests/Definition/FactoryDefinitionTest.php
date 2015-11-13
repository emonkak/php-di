<?php

namespace Emonkak\Di\Tests\Definition
{
    use Emonkak\Di\Container;
    use Emonkak\Di\Definition\FactoryDefinition;
    use Emonkak\Di\Tests\Definition\FactoryDefinitionTest\Bar;
    use Emonkak\Di\Tests\Definition\FactoryDefinitionTest\Baz;
    use Emonkak\Di\Tests\Definition\FactoryDefinitionTest\Foo;
    use Emonkak\Di\Tests\Definition\FactoryDefinitionTest\FooFactory;
    use SuperClosure\SerializableClosure;

    class FactoryDefinitionTest extends \PHPUnit_Framework_TestCase
    {
        public function testResolveDependency()
        {
            \Closure::bind(function() {
                $factory = new FooFactory();
                $definition = new FactoryDefinition('Emonkak\Di\Tests\Definition\FactoryDefinitionTest\Foo', $factory);

                $container = Container::create();
                $bazDefinition = $container->set('$baz', new Baz());
                $barDefinition = $container->factory('$bar', function() {
                    return new Bar(new Baz());
                });
                $fooDependency = $definition
                    ->with([$barDefinition, $bazDefinition])
                    ->resolveDependency($container);

                $this->assertSame('Emonkak\Di\Dependency\FactoryDependency', get_class($fooDependency));
                $this->assertSame('Emonkak\Di\Tests\Definition\FactoryDefinitionTest\Foo', $fooDependency->getKey());
                $this->assertEquals($factory, $fooDependency->getFactory());
                $this->assertEquals([$barDefinition->resolveBy($container), $bazDefinition->resolveBy($container)], $fooDependency->getParameters());
            }, $this, 'Emonkak\Di\Definition\FactoryDefinition')->__invoke();
        }

        public function testResolveDependencyWithClosure()
        {
            \Closure::bind(function() {
                $factory = function() {
                    return new Foo(new Bar(new Baz()), new Baz());
                };
                $definition = new FactoryDefinition('Emonkak\Di\Tests\Definition\FactoryDefinitionTest\Foo', $factory);

                $container = Container::create();
                $fooDependency = $definition->resolveBy($container);

                $this->assertSame('Emonkak\Di\Dependency\FactoryDependency', get_class($fooDependency));
                $this->assertSame('Emonkak\Di\Tests\Definition\FactoryDefinitionTest\Foo', $fooDependency->getKey());
                $this->assertInstanceOf('SuperClosure\SerializableClosure', $fooDependency->getFactory());
                $this->assertSame((new SerializableClosure($factory))->serialize(), $fooDependency->getFactory()->serialize());
                $this->assertEquals([], $fooDependency->getParameters());
            }, $this, 'Emonkak\Di\Definition\FactoryDefinition')->__invoke();
        }

        public function testResolveScope()
        {
            \Closure::bind(function() {
                $factory = new FooFactory();
                $definition = new FactoryDefinition('Emonkak\Di\Tests\Definition\FactoryDefinitionTest\Foo', $factory);
                $container = $this->getMock('Emonkak\Di\ContainerInterface');

                $this->assertInstanceOf('Emonkak\Di\Scope\PrototypeScope', $definition->resolveScope($container));
            }, $this, 'Emonkak\Di\Definition\AliasDefinition')->__invoke();
        }
    }
}

namespace Emonkak\Di\Tests\Definition\FactoryDefinitionTest
{
    class FooFactory
    {
        public function __invoke(BarInterface $bar, BazInterface $baz)
        {
            return new Foo($bar, $baz);
        }
    }

    class Foo
    {
        public $bar;
        public $baz;

        public function __construct(BarInterface $bar, BazInterface $baz)
        {
            $this->bar = $bar;
            $this->baz = $baz;
        }
    }

    class Bar implements BarInterface
    {
        public $baz;

        public function __construct(BazInterface $baz)
        {
            $this->baz = $baz;
        }
    }

    class Baz implements BazInterface
    {
    }

    interface BarInterface
    {
    }

    interface BazInterface
    {
    }
}
