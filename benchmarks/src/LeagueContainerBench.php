<?php

namespace Emonkak\Di\Benchmarks;

use Emonkak\Di\Benchmarks\Fixtures\Bar;
use Emonkak\Di\Benchmarks\Fixtures\Baz;
use Emonkak\Di\Benchmarks\Fixtures\Corge;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\FooInterface;
use Emonkak\Di\Benchmarks\Fixtures\Grault;
use Emonkak\Di\Benchmarks\Fixtures\Quux;
use Emonkak\Di\Benchmarks\Fixtures\Qux;
use League\Container\Container;
use League\Container\ReflectionContainer;

/**
 * @Groups({"di"})
 */
class LeagueContainerBench
{
    public function benchGet()
    {
        $container = new Container();
        $container->delegate(new ReflectionContainer());

        $container->add(FooInterface::class, Foo::class)
            ->withArgument(BarInterface::class)
            ->withArgument(BazInterface::class);
        $container->add(BarInterface::class, Bar::class)
            ->withArgument(Qux::class)
            ->withArgument(Quux::class);
        $container->add(BazInterface::class, Baz::class)
            ->withArgument(Corge::class)
            ->withArgument(Grault::class);

        assert($container->get(FooInterface::class) instanceof Foo);
    }
}
