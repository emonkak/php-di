<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use League\Di\Container;

class LeagueDiEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function get()
    {
        $container = new Container();

        $container->bind('Emonkak\Di\Benchmarks\Fixtures\FooInterface', 'Emonkak\Di\Benchmarks\Fixtures\Foo');
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BarInterface', 'Emonkak\Di\Benchmarks\Fixtures\Bar');
        $container->bind('Emonkak\Di\Benchmarks\Fixtures\BazInterface', 'Emonkak\Di\Benchmarks\Fixtures\Baz');

        $foo = $container->resolve('Emonkak\Di\Benchmarks\Fixtures\FooInterface');
        assert($foo instanceof Foo);
    }
}
