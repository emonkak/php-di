<?php

declare(strict_types=1);

namespace Emonkak\Di\Benchmarks;

use Emonkak\Di\Benchmarks\Fixtures\Bar;
use Emonkak\Di\Benchmarks\Fixtures\BarInterface;
use Emonkak\Di\Benchmarks\Fixtures\Baz;
use Emonkak\Di\Benchmarks\Fixtures\BazInterface;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\FooInterface;
use Illuminate\Container\Container;

/**
 * @Groups({"di"})
 */
class IlluminateContainerBench
{
    public function benchGet()
    {
        $container = new Container();
        $container->bind(FooInterface::class, Foo::class);
        $container->bind(BarInterface::class, Bar::class);
        $container->bind(BazInterface::class, Baz::class);
        assert($container->make(FooInterface::class) instanceof Foo);
    }
}
