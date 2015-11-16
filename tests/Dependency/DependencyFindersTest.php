<?php

namespace Emonkak\Di\Tests\Dependency
{
    use Emonkak\Di\Container;
    use Emonkak\Di\Dependency\DependencyFinders;
    use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
    use Emonkak\Di\Tests\Dependency\DependencyFindersTest\Bar;

    class DependencyFindersTest extends \PHPUnit_Framework_TestCase
    {
        public function testResolveParameterDependency()
        {
            $injectionPolicy = new DefaultInjectionPolicy();
            $container = Container::create($injectionPolicy);

            $foo = new \ReflectionClass('Emonkak\Di\Tests\Dependency\DependencyFindersTest\Foo');
            $parameters = $foo->getConstructor()->getParameters();

            $barDependency = $container->resolve('Emonkak\Di\Tests\Dependency\DependencyFindersTest\Bar');

            $this->assertEquals($barDependency, DependencyFinders::resolveParameterDependency($container, $injectionPolicy, $parameters[0]));
            $this->assertEquals($barDependency, DependencyFinders::resolveParameterDependency($container, $injectionPolicy, $parameters[1]));
            $this->assertNull(DependencyFinders::resolveParameterDependency($container, $injectionPolicy, $parameters[2]));
        }

        public function testResolvePropertyDependency()
        {
            $injectionPolicy = new DefaultInjectionPolicy();
            $container = Container::create($injectionPolicy);

            $foo = new \ReflectionClass('Emonkak\Di\Tests\Dependency\DependencyFindersTest\Foo');

            $barDependency = $container->set('$bar', new Bar());
            $optionalBarDependency = $container->set('$optionalBar', new Bar());

            $this->assertEquals($barDependency, DependencyFinders::resolvePropertyDependency($container, $injectionPolicy, $foo->getProperty('bar')));
            $this->assertEquals($optionalBarDependency, DependencyFinders::resolvePropertyDependency($container, $injectionPolicy, $foo->getProperty('optionalBar')));
            $this->assertNull(DependencyFinders::resolvePropertyDependency($container, $injectionPolicy, $foo->getProperty('optionalBaz')));
        }
    }
}

namespace Emonkak\Di\Tests\Dependency\DependencyFindersTest
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
