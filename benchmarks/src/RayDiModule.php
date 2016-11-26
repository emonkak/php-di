<?php

namespace Emonkak\Di\Benchmarks;

use Ray\Di\AbstractModule;

class RayDiModule extends AbstractModule
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
