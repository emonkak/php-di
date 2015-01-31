<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Doctrine\Common\Cache\ApcCache;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Ray\Di\AbstractModule;
use Ray\Di\CacheInjector;
use Ray\Di\Injector;

class RayDiEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function get()
    {
        $injector = Injector::create([new MyModule()]);
        $foo = $injector->getInstance('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }

    /**
     * @iterations 1000
     */
    public function getWithCache()
    {
        $injector = new CacheInjector(
            function() {
                return Injector::create([new MyModule()]);
            },
            function() {},
            'ray-di',
            new ApcCache()
        );
        $foo = $injector->getInstance('Emonkak\Di\Benchmarks\Fixtures\Foo');
        assert($foo instanceof Foo);
    }
}

class MyModule extends AbstractModule
{
    public function configure()
    {
        $this->bind('Emonkak\Di\Benchmarks\Fixtures\BarInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Bar');
        $this->bind('Emonkak\Di\Benchmarks\Fixtures\BazInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Baz');
    }
}
