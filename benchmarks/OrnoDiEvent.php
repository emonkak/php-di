<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Orno\Cache\Adapter\ApcAdapter;
use Orno\Cache\Cache;
use Orno\Di\Container;

class OrnoDiEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function get()
    {
        $container = new Container();
        $container->add('Emonkak\Di\Benchmarks\Fixtures\Foo')
            ->withArgument('Emonkak\Di\Benchmarks\Fixtures\Bar')
            ->withArgument('Emonkak\Di\Benchmarks\Fixtures\Baz');
        $foo = $container->get('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }

    /**
     * @iterations 1000
     */
    public function getWithCache()
    {
        $container = new Container(new Cache(new ApcAdapter()));
        $container->add('Emonkak\Di\Benchmarks\Fixtures\Foo')
            ->withArgument('Emonkak\Di\Benchmarks\Fixtures\Bar')
            ->withArgument('Emonkak\Di\Benchmarks\Fixtures\Baz');
        $foo = $container->get('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }
}
