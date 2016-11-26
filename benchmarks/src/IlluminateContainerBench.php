<?php

namespace Emonkak\Di\Benchmarks;

use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Illuminate\Container\Container;

/**
 * @Groups({"di"})
 */
class IlluminateContainerBench
{
    public function benchGet()
    {
        $container = new Container();
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\FooInterface', 'Emonkak\Di\Benchmarks\Fixtures\Foo');
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BarInterface', 'Emonkak\Di\Benchmarks\Fixtures\Bar');
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BazInterface', 'Emonkak\Di\Benchmarks\Fixtures\Baz');
        assert($container->make('Emonkak\Di\Benchmarks\Fixtures\FooInterface') instanceof Foo);
    }
}
