<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\ChainCache;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Container;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\PimpleContainer;

class EmonkakDiEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function get()
    {
        $container = Container::create();
        $foo = $container->getInstance('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }

    /**
     * @iterations 1000
     */
    public function getWithApcCache()
    {
        $container = new Container(
            new DefaultInjectionPolicy(),
            new ChainCache([new ArrayCache(), new ApcCache()]),
            new \ArrayObject()
        );
        $foo = $container->getInstance('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }

    /**
     * @iterations 1000
     */
    public function getWithPimple()
    {
        $container = PimpleContainer::create();
        $foo = $container->getInstance('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }
}
