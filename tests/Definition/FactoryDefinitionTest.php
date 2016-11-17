<?php

namespace Emonkak\Di\Tests\Definition
{
    use Emonkak\Di\Container;
    use Emonkak\Di\Definition\FactoryDefinition;
    use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
    use Emonkak\Di\Tests\Definition\FactoryDefinitionTest\Bar;
    use Emonkak\Di\Tests\Definition\FactoryDefinitionTest\Baz;
    use Emonkak\Di\Tests\Definition\FactoryDefinitionTest\Foo;
    use Emonkak\Di\Tests\Definition\FactoryDefinitionTest\FooFactory;
    use SuperClosure\SerializableClosure;

    class FactoryDefinitionTest extends \PHPUnit_Framework_TestCase
    {
        public function testResolveDependency()
        {
            $factory = new FooFactory();
            $definition = new FactoryDefinition('Emonkak\Di\Tests\Definition\FactoryDefinitionTest\Foo', $factory);

            $injectionPolicy = new DefaultInjectionPolicy();
            $container = Container::create($injectionPolicy);
            $bazDefinition = $container->set('$baz', new Baz());
            $barDefinition = $container->factory('$bar', function() {
                return new Bar(new Baz());
            });
            $fooDependency = $definition
                ->with([$barDefinition, $bazDefinition])
                ->resolveBy($container, $injectionPolicy);

            $this->assertInstanceOf('Emonkak\Di\Dependency\FactoryDependency', $fooDependency);
            $this->assertSame('Emonkak\Di\Tests\Definition\FactoryDefinitionTest\Foo', $fooDependency->getKey());
            $this->assertEquals($factory, $fooDependency->getFactory());
            $this->assertEquals([$barDefinition->resolveBy($container, $injectionPolicy), $bazDefinition->resolveBy($container, $injectionPolicy)], $fooDependency->getParameters());
        }

        public function testResolveByWithClosure()
        {
            $factory = function() {
                return new Foo(new Bar(new Baz()), new Baz());
            };
            $definition = new FactoryDefinition('Emonkak\Di\Tests\Definition\FactoryDefinitionTest\Foo', $factory);

            $injectionPolicy = new DefaultInjectionPolicy();
            $container = Container::create($injectionPolicy);
            $fooDependency = $definition->resolveBy($container, $injectionPolicy);

            $this->assertInstanceOf('Emonkak\Di\Dependency\FactoryDependency', $fooDependency);
            $this->assertSame('Emonkak\Di\Tests\Definition\FactoryDefinitionTest\Foo', $fooDependency->getKey());
            $this->assertInstanceOf('SuperClosure\SerializableClosure', $fooDependency->getFactory());
            $this->assertSame((new SerializableClosure($factory))->serialize(), $fooDependency->getFactory()->serialize());
            $this->assertEquals([], $fooDependency->getParameters());
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
