<?php

namespace Emonkak\Di\Benchmarks;

use Brick\Di\Container;
use Emonkak\Di\Benchmarks\Fixtures\Bar;
use Emonkak\Di\Benchmarks\Fixtures\BarInterface;
use Emonkak\Di\Benchmarks\Fixtures\Baz;
use Emonkak\Di\Benchmarks\Fixtures\BazInterface;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\FooInterface;

/**
 * @Groups({"di"})
 */
class BrickDiBench
{
    public function benchGet()
    {
        $container = new Container(new BrickDiPolicy());
        $container->bind(FooInterface::class)->to(Foo::class);
        $container->bind(BarInterface::class)->to(Bar::class);
        $container->bind(BazInterface::class)->to(Baz::class);
        assert($container->get(FooInterface::class) instanceof Foo);
    }
}
