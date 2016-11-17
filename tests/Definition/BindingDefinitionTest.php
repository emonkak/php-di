<?php

namespace Emonkak\Di\Tests\Definition
{
    use Emonkak\Di\Container;
    use Emonkak\Di\Definition\BindingDefinition;
    use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
    use Emonkak\Di\Tests\Definition\BindingDefinitionTest\Bar;
    use Emonkak\Di\Tests\Definition\BindingDefinitionTest\Baz;
    use Emonkak\Di\Tests\Definition\BindingDefinitionTest\Foo;
    use Emonkak\Di\Tests\Definition\BindingDefinitionTest\Qux;

    class BindingDefinitionTest extends \PHPUnit_Framework_TestCase
    {
        public function testResolveBy()
        {
            $definition = new BindingDefinition('Emonkak\Di\Tests\Definition\BindingDefinitionTest\FooInterface');

            $injectionPolicy = new DefaultInjectionPolicy();
            $container = Container::create($injectionPolicy);
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
                ->resolveBy($container, $injectionPolicy);

            $this->assertInstanceOf('Emonkak\Di\Dependency\ObjectDependency', $fooDependency);
            $this->assertSame('Emonkak\Di\Tests\Definition\BindingDefinitionTest\FooInterface', $fooDependency->getKey());
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

            $definition = new BindingDefinition('Emonkak\Di\Tests\Definition\BindingDefinitionTest\FooInterface');
            $definition->resolveBy($container, $injectionPolicy);
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
