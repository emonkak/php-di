<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Cache\ApcCache;
use Emonkak\Di\Cache\FilesystemCache;
use Emonkak\Di\Container;
use Emonkak\Di\Extras\ServiceProviderGenerator;
use Emonkak\Di\Extras\ServiceProviderLoader;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\PimpleContainer;
use Pimple\Container as Pimple;

class EmonkakDiEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function get()
    {
        $container = Container::create();
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BarInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Bar');
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BazInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Baz');
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
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BarInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Bar');
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BazInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Baz');
        $foo = $container->getInstance('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }

    /**
     * @iterations 1000
     */
    public function getWithPimple()
    {
        $container = PimpleContainer::create();
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BarInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Bar');
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BazInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Baz');
        $foo = $container->getInstance('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }
}
