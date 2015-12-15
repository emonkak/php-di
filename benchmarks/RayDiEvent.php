<?php

namespace Emonkak\Di\Benchmarks;

use Athletic\AthleticEvent;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ApcuCache;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Ray\Di\AbstractModule;
use Ray\Di\CacheInjector;
use Ray\Di\Injector;

class RayDiEvent extends AthleticEvent
{
    public function setUp()
    {
        $injector = new Injector(new MyModule());
        $this->cachedInjector = serialize($injector);
    }

    /**
     * @iterations 1000
     */
    public function get()
    {
        $injector = new Injector(new MyModule());
        $foo = $injector->getInstance('Emonkak\Di\Benchmarks\Fixtures\FooInterface');
        assert($foo instanceof Foo);
    }

    /**
     * @iterations 1000
     */
    public function getWithCache()
    {
        $injector = unserialize($this->cachedInjector);
        $foo = $injector->getInstance('Emonkak\Di\Benchmarks\Fixtures\FooInterface');
        assert($foo instanceof Foo);
    }
}

class MyModule extends AbstractModule
{
    protected function configure()
    {
        $this->bind('Emonkak\Di\Benchmarks\Fixtures\FooInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Foo');
        $this->bind('Emonkak\Di\Benchmarks\Fixtures\BarInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Bar');
        $this->bind('Emonkak\Di\Benchmarks\Fixtures\BazInterface')->to('Emonkak\Di\Benchmarks\Fixtures\Baz');
        $this->bind('Emonkak\Di\Benchmarks\Fixtures\Qux');
        $this->bind('Emonkak\Di\Benchmarks\Fixtures\Quux');
        $this->bind('Emonkak\Di\Benchmarks\Fixtures\Corge');
        $this->bind('Emonkak\Di\Benchmarks\Fixtures\Grault');
        $this->bind('Emonkak\Di\Benchmarks\Fixtures\Garply');
        $this->bind('Emonkak\Di\Benchmarks\Fixtures\Waldo');
        $this->bind('Emonkak\Di\Benchmarks\Fixtures\Fred');
        $this->bind('Emonkak\Di\Benchmarks\Fixtures\Plugh');
    }
}
