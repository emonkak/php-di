<?php

namespace Emonkak\Di\Benchmarks;

use Brick\Di\Container;
use Emonkak\Di\Benchmarks\Fixtures\Foo;

/**
 * @Groups({"di"})
 */
class BrickDiBench
{
    public function benchGet()
    {
        $container = new Container(new BrickDiPolicy());
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\FooInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Foo');
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BarInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Bar');
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BazInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Baz');
        assert($container->get('Emonkak\Di\Benchmarks\Fixtures\FooInterface') instanceof Foo);
    }
}
