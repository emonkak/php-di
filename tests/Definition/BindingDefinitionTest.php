<?php

namespace Emonkak\Di\Tests\Definition
{
    use Emonkak\Di\Container;
    use Emonkak\Di\Definition\BindingDefinition;
    use Emonkak\Di\Tests\Definition\BindingDefinitionTest\Foo;
    use Emonkak\Di\Tests\Definition\BindingDefinitionTest\Bar;
    use Emonkak\Di\Tests\Definition\BindingDefinitionTest\Baz;
    use Emonkak\Di\Tests\Definition\BindingDefinitionTest\Qux;

    class BindingDefinitionTest extends \PHPUnit_Framework_TestCase
    {
        public function testResolve()
        {
            \Closure::bind(function() {
                $definition = new BindingDefinition('Emonkak\Di\Tests\Definition\BindingDefinitionTest\FooInterface');

                $container = Container::create();
                $barDefinition = $container->set('Emonkak\Di\Tests\Definition\BindingDefinitionTest\Bar', new Bar());
                $bazDefinition = $container->set('Emonkak\Di\Tests\Definition\BindingDefinitionTest\Baz', new Baz());
                $quxDefinition = $container->factory('Emonkak\Di\Tests\Definition\BindingDefinitionTest\Qux', function() {
                    return new Qux();
                });

                $fooDependency = $definition
                    ->to('Emonkak\Di\Tests\Definition\BindingDefinitionTest\Foo')
                    ->with([$barDefinition])
                    ->withMethod('setBaz', [$bazDefinition])
                    ->withProperty('qux', $quxDefinition)
                    ->resolve($container);

                $this->assertSame('Emonkak\Di\Dependency\ObjectDependency', get_class($fooDependency));
                $this->assertSame('Emonkak\Di\Tests\Definition\BindingDefinitionTest\FooInterface', $fooDependency->getKey());
                $this->assertEquals([$barDefinition->get($container)], $fooDependency->getConstructorParameters());
                $this->assertEquals(['setBaz' => [$bazDefinition->get($container)]], $fooDependency->getMethodInjections());
                $this->assertEquals(['qux' => $quxDefinition->get($container)], $fooDependency->getPropertyInjections());
            }, $this, 'Emonkak\Di\Definition\BindingDefinition')->__invoke();
        }

        /**
         * @expectedException LogicException
         */
        public function testResolveThrowsLogicException()
        {
            \Closure::bind(function() {
                $definition = new BindingDefinition('Emonkak\Di\Tests\Definition\BindingDefinitionTest\FooInterface');
                $definition->resolve(Container::create());
            }, $this, 'Emonkak\Di\Definition\BindingDefinition')->__invoke();
        }
    }
}

namespace Emonkak\Di\Tests\Definition\BindingDefinitionTest
{
    class Foo implements FooInterface
    {
        public $bar;
        public $baz;
        public $qux;

        public function __construct(Bar $bar)
        {
            $this->bar = $bar;
        }

        public function setBaz(Baz $baz)
        {
            $this->baz = $baz;
        }
    }

    class Bar
    {
        public $baz;

        public function setBaz()
        {
            $this->baz = $baz;
        }
    }

    class Baz
    {
        public $qux;
    }

    class Qux
    {
    }

    interface FooInterface
    {
    }
}
