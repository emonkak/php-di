<?php

namespace Emonkak\Di\Benchmarks;

use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Cache\ApcCache;
use Emonkak\Di\Cache\ApcuCache;
use Emonkak\Di\Cache\FilesystemCache;
use Emonkak\Di\Container;
use Emonkak\Di\Extras\ServiceProviderGenerator;
use Emonkak\Di\Extras\ServiceProviderLoader;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\PimpleContainer;
use Pimple\Container as Pimple;

/**
 * @Groups({"di"})
 */
class EmonkakDiBench
{
    public function benchGet()
    {
        $container = Container::create();
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\FooInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Foo');
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BarInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Bar');
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BazInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Baz');
        assert($container->get('Emonkak\Di\Benchmarks\Fixtures\FooInterface') instanceof Foo);
    }

    public function benchGetWithApcCache()
    {
        $container = new Container(
            new DefaultInjectionPolicy(),
            extension_loaded('apcu') ? new ApcuCache('container') : new ApcCache('container'),
            new \ArrayObject()
        );
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\FooInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Foo');
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BarInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Bar');
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BazInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Baz');
        assert($container->get('Emonkak\Di\Benchmarks\Fixtures\Foo') instanceof Foo);
    }

    public function benchGetWithPimple()
    {
        $container = PimpleContainer::create();
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\FooInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Foo');
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BarInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Bar');
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BazInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Baz');
        assert($container->get('Emonkak\Di\Benchmarks\Fixtures\Foo') instanceof Foo);
    }
}
