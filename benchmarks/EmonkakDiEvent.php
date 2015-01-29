<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Cache\ApcCache;
use Emonkak\Di\Cache\FilesystemCache;
use Emonkak\Di\Container;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\PimpleContainer;
use Emonkak\Di\ServiceProvider\ServiceProviderGenerator;
use Emonkak\Di\ServiceProvider\ServiceProviderLoader;
use Pimple\Container as Pimple;

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
            new ApcCache('container'),
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
