<?php

namespace Emonkak\Di\Benchmarks;

use Emonkak\Di\Benchmarks\Fixtures\Bar;
use Emonkak\Di\Benchmarks\Fixtures\BarInterface;
use Emonkak\Di\Benchmarks\Fixtures\Baz;
use Emonkak\Di\Benchmarks\Fixtures\BazInterface;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\FooInterface;
use Emonkak\Di\Cache\ApcuCache;
use Emonkak\Di\Container;
use Emonkak\Di\Inspector\Inspector;
use Emonkak\Di\Instantiator\Instantiator;
use Pimple\Container as Pimple;

/**
 * @Groups({"di"})
 */
class EmonkakDiBench
{
    public function benchGet()
    {
        $container = new Container(
            Inspector::createDefault(),
            new Instantiator()
        );
        $container->implement(FooInterface::class, Foo::class);
        $container->implement(BarInterface::class, Bar::class);
        $container->implement(BazInterface::class, Baz::class);
        assert($container->get(FooInterface::class) instanceof Foo);
    }

    public function benchGetWithCache()
    {
        $container = new Container(
            Inspector::createDefault()->withCache(new ApcuCache()),
            new Instantiator()
        );
        $container->implement(FooInterface::class, Foo::class);
        $container->implement(BarInterface::class, Bar::class);
        $container->implement(BazInterface::class, Baz::class);
        assert($container->get(Foo::class) instanceof Foo);
    }
}
