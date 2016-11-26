<?php

use Emonkak\Di\Container;
use Emonkak\Di\Dependency\DependencyFinders;
use Emonkak\Di\Dependency\ValueDependency;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\Tests\Dependency\Stubs\Bar;

/**
 * @covers Emonkak\Di\Dependency\DependencyFinders
 */
class DependencyFindersTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveParameterDependency()
    {
        $injectionPolicy = new DefaultInjectionPolicy();
        $container = Container::create($injectionPolicy);

        $optional = new \ReflectionClass('Emonkak\Di\Tests\Dependency\Stubs\Optional');
        $parameters = $optional->getConstructor()->getParameters();

        $barDependency = $container->resolve('Emonkak\Di\Tests\Dependency\Stubs\Bar');
        $optionalBarDependency = new ValueDependency(null);
        $optionalBazDependency = new ValueDependency(null);

        $this->assertEquals($barDependency, DependencyFinders::resolveParameterDependency($container, $injectionPolicy, $parameters[0]));
        $this->assertEquals($optionalBarDependency, DependencyFinders::resolveParameterDependency($container, $injectionPolicy, $parameters[1]));
        $this->assertEquals($optionalBazDependency, DependencyFinders::resolveParameterDependency($container, $injectionPolicy, $parameters[2]));
    }

    public function testResolvePropertyDependency()
    {
        $injectionPolicy = new DefaultInjectionPolicy();
        $container = Container::create($injectionPolicy);

        $optional = new \ReflectionClass('Emonkak\Di\Tests\Dependency\Stubs\Optional');

        $barDependency = $container->set('$bar', new Bar());
        $optionalBarDependency = $container->set('$optionalBar', new Bar());
        $optionalBazDependency = new ValueDependency(123);

        $this->assertEquals($barDependency, DependencyFinders::resolvePropertyDependency($container, $injectionPolicy, $optional->getProperty('bar')));
        $this->assertEquals($optionalBarDependency, DependencyFinders::resolvePropertyDependency($container, $injectionPolicy, $optional->getProperty('optionalBar')));
        $this->assertEquals($optionalBazDependency, DependencyFinders::resolvePropertyDependency($container, $injectionPolicy, $optional->getProperty('optionalBaz')));
    }
}
