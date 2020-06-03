<?php

declare(strict_types=1);

namespace Emonkak\Di\Benchmarks;

use Emonkak\Di\Benchmarks\Fixtures\Bar;
use Emonkak\Di\Benchmarks\Fixtures\BarInterface;
use Emonkak\Di\Benchmarks\Fixtures\Baz;
use Emonkak\Di\Benchmarks\Fixtures\BazInterface;
use Emonkak\Di\Benchmarks\Fixtures\Corge;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\FooInterface;
use Emonkak\Di\Benchmarks\Fixtures\Fred;
use Emonkak\Di\Benchmarks\Fixtures\Garply;
use Emonkak\Di\Benchmarks\Fixtures\Grault;
use Emonkak\Di\Benchmarks\Fixtures\Plugh;
use Emonkak\Di\Benchmarks\Fixtures\Quux;
use Emonkak\Di\Benchmarks\Fixtures\Qux;
use Emonkak\Di\Benchmarks\Fixtures\Waldo;
use mindplay\unbox\ContainerFactory;

/**
 * @Groups({"di"})
 */
class UnboxBench
{
    public function benchGet()
    {
        $factory = new ContainerFactory();
        $factory->register(FooInterface::class, Foo::class);
        $factory->register(BarInterface::class, Bar::class);
        $factory->register(BazInterface::class, Baz::class);
        $factory->register(Qux::class);
        $factory->register(Quux::class);
        $factory->register(Corge::class);
        $factory->register(Grault::class);
        $factory->register(Garply::class);
        $factory->register(Waldo::class);
        $factory->register(Fred::class);
        $factory->register(Plugh::class);
        $container = $factory->createContainer();
        assert($container->get(FooInterface::class) instanceof Foo);
    }
}
