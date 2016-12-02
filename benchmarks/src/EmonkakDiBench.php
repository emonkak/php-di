<?php

namespace Emonkak\Di\Benchmarks;

use Emonkak\Di\Benchmarks\Fixtures\Bar;
use Emonkak\Di\Benchmarks\Fixtures\BarInterface;
use Emonkak\Di\Benchmarks\Fixtures\Baz;
use Emonkak\Di\Benchmarks\Fixtures\BazInterface;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\FooInterface;
use Emonkak\Di\Cache\ApcCache;
use Emonkak\Di\Cache\ApcuCache;
use Emonkak\Di\Cache\FilesystemCache;
use Emonkak\Di\Container;
use Emonkak\Di\Extras\ServiceProviderGenerator;
use Emonkak\Di\Extras\ServiceProviderLoader;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Pimple\Container as Pimple;

/**
 * @Groups({"di"})
 */
class EmonkakDiBench
{
    public function benchGet()
    {
        $container = new Container(
            new DefaultInjectionPolicy(),
            new \ArrayObject()
        );
        $container->bind(FooInterface::class)->to(Foo::class);
        $container->bind(BarInterface::class)->to(Bar::class);
        $container->bind(BazInterface::class)->to(Baz::class);
        assert($container->get(FooInterface::class) instanceof Foo);
    }

    public function benchGetWithApcCache()
    {
        $container = new Container(
            new DefaultInjectionPolicy(),
            extension_loaded('apcu') ? new ApcuCache('container') : new ApcCache('container')
        );
        $container->bind(FooInterface::class)->to(Foo::class);
        $container->bind(BarInterface::class)->to(Bar::class);
        $container->bind(BazInterface::class)->to(Baz::class);
        assert($container->get(Foo::class) instanceof Foo);
    }
}
