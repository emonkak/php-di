<?php

namespace Emonkak\Di\Tests
{
    use Emonkak\Di\Container;
    use Emonkak\Di\InjectionFinder;
    use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
    use Emonkak\Di\Tests\DependencyResolver\ContainerDependencyResolverTest\Bar;

    class InjectionFinderTest extends \PHPUnit_Framework_TestCase
    {
        public function testGetParameterDependency()
        {
            $injectionPolicy = new DefaultInjectionPolicy();
            $container = Container::create($injectionPolicy);
            $injectionFinder = new InjectionFinder($container, $injectionPolicy);

            $foo = new \ReflectionClass('Emonkak\Di\Tests\DependencyResolver\ContainerDependencyResolverTest\Foo');
            $parameters = $foo->getConstructor()->getParameters();

            $barDependency = $container->resolve('Emonkak\Di\Tests\DependencyResolver\ContainerDependencyResolverTest\Bar');

            $this->assertEquals($barDependency, $injectionFinder->getParameterDependency($parameters[0]));
            $this->assertEquals($barDependency, $injectionFinder->getParameterDependency($parameters[1]));
            $this->assertNull($injectionFinder->getParameterDependency($parameters[2]));
        }

        public function testGetPropertyDependency()
        {
            $injectionPolicy = new DefaultInjectionPolicy();
            $container = Container::create($injectionPolicy);
            $injectionFinder = new InjectionFinder($container, $injectionPolicy);

            $foo = new \ReflectionClass('Emonkak\Di\Tests\DependencyResolver\ContainerDependencyResolverTest\Foo');

            $barDependency = $container->set('$bar', new Bar());
            $optionalBarDependency = $container->set('$optionalBar', new Bar());

            $this->assertEquals($barDependency, $injectionFinder->getPropertyDependency($foo->getProperty('bar')));
            $this->assertEquals($optionalBarDependency, $injectionFinder->getPropertyDependency($foo->getProperty('optionalBar')));
            $this->assertNull($injectionFinder->getPropertyDependency($foo->getProperty('optionalBaz')));
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
