<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Brick\Di\Container;
use Brick\Di\InjectionPolicy;
use Emonkak\Di\Benchmarks\Fixtures\Foo;

if (version_compare(PHP_VERSION, '5.5', '<')) {
    class BrickDiEvent extends AthleticEvent
    {
    }
} else {
    class BrickDiEvent extends AthleticEvent
    {
        /**
        * @iterations 1000
        */
        public function get()
        {
            $container = new Container(new MyPolicy());
            $container->bind('Emonkak\Di\Benchmarks\Fixtures\FooInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Foo');
            $container->bind('Emonkak\Di\Benchmarks\Fixtures\BarInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Bar');
            $container->bind('Emonkak\Di\Benchmarks\Fixtures\BazInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Baz');
            $foo = $container->get('Emonkak\Di\Benchmarks\Fixtures\FooInterface');
            assert($foo instanceof Foo);
        }
    }
}

class MyPolicy implements InjectionPolicy
{
    /**
     * {@inheritdoc}
     */
    public function isClassInjected(\ReflectionClass $class)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isMethodInjected(\ReflectionMethod $method)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isPropertyInjected(\ReflectionProperty $property)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterKey(\ReflectionParameter $parameter)
    {
        return $parameter->getClass()->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getPropertyKey(\ReflectionProperty $property)
    {
        return $property->getClass()->name;
    }
}
