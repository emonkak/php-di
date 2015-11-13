<?php

namespace Emonkak\Di\Tests\DependencyResolver
{
    use Emonkak\Di\Container;
    use Emonkak\Di\DependencyResolver\ContainerDependencyResolver;
    use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
    use Emonkak\Di\Tests\DependencyResolver\ContainerDependencyResolverTest\Bar;

    class ContainerDependencyResolverTest extends \PHPUnit_Framework_TestCase
    {
        public function testGetParameterDependency()
        {
            $injectionPolicy = new DefaultInjectionPolicy();
            $container = Container::create($injectionPolicy);
            $dependencyResolver = new ContainerDependencyResolver($container, $injectionPolicy);

            $foo = new \ReflectionClass('Emonkak\Di\Tests\DependencyResolver\ContainerDependencyResolverTest\Foo');
            $parameters = $foo->getConstructor()->getParameters();

            $barDependency = $container->resolve('Emonkak\Di\Tests\DependencyResolver\ContainerDependencyResolverTest\Bar');

            $this->assertEquals($barDependency, $dependencyResolver->getParameterDependency($parameters[0]));
            $this->assertEquals($barDependency, $dependencyResolver->getParameterDependency($parameters[1]));
            $this->assertNull($dependencyResolver->getParameterDependency($parameters[2]));
        }

        public function testGetPropertyDependency()
        {
            $injectionPolicy = new DefaultInjectionPolicy();
            $container = Container::create($injectionPolicy);
            $dependencyResolver = new ContainerDependencyResolver($container, $injectionPolicy);

            $foo = new \ReflectionClass('Emonkak\Di\Tests\DependencyResolver\ContainerDependencyResolverTest\Foo');

            $barDependency = $container->set('$bar', new Bar());
            $optionalBarDependency = $container->set('$optionalBar', new Bar());

            $this->assertEquals($barDependency, $dependencyResolver->getPropertyDependency($foo->getProperty('bar')));
            $this->assertEquals($optionalBarDependency, $dependencyResolver->getPropertyDependency($foo->getProperty('optionalBar')));
            $this->assertNull($dependencyResolver->getPropertyDependency($foo->getProperty('optionalBaz')));
        }
    }
}

namespace Emonkak\Di\Tests\DependencyResolver\ContainerDependencyResolverTest
{
    class Foo
    {
        public $bar;
        public $optionalBar = 123;
        public $optionalBaz = 123;

        public function __construct(Bar $bar, Bar $optionalBar = null, BazInterface $optionalBaz = null)
        {
        }
    }

    class Bar
    {
    }

    interface BazInterface
    {
    }
}
