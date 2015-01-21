<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Orno\Cache\Adapter\ApcAdapter;
use Orno\Cache\Cache;
use Orno\Di\Container;

class OrnoDiEvent extends AthleticEvent
{
    public function setUp()
    {
        $this->cache = new Cache(new ApcAdapter());
    }

    /**
     * @iterations 1000
     */
    public function get()
    {
        $container = new Container();
        $foo = $container->get('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }

    /**
     * @iterations 1000
     */
    public function getWithCache()
    {
        $container = new Container($this->cache);
        $foo = $container->get('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }
}
