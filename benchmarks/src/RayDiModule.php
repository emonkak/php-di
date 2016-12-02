<?php

namespace Emonkak\Di\Benchmarks;

use Emonkak\Di\Benchmarks\Fixtures\Bar;
use Emonkak\Di\Benchmarks\Fixtures\BarInterface;
use Emonkak\Di\Benchmarks\Fixtures\Baz;
use Emonkak\Di\Benchmarks\Fixtures\BazInterface;
use Emonkak\Di\Benchmarks\Fixtures\Corge;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\FooInterface;
use Emonkak\Di\Benchmarks\Fixtures\Fred;
use Emonkak\Di\Benchmarks\Fixtures\Garply;
use Emonkak\Di\Benchmarks\Fixtures\Grault;
use Emonkak\Di\Benchmarks\Fixtures\Plugh;
use Emonkak\Di\Benchmarks\Fixtures\Quux;
use Emonkak\Di\Benchmarks\Fixtures\Qux;
use Emonkak\Di\Benchmarks\Fixtures\Waldo;
use Ray\Di\AbstractModule;

class RayDiModule extends AbstractModule
{
    protected function configure()
    {
        $this->bind(FooInterface::class)->to(Foo::class);
        $this->bind(BarInterface::class)->to(Bar::class);
        $this->bind(BazInterface::class)->to(Baz::class);
        $this->bind(Qux::class);
        $this->bind(Quux::class);
        $this->bind(Corge::class);
        $this->bind(Grault::class);
        $this->bind(Garply::class);
        $this->bind(Waldo::class);
        $this->bind(Fred::class);
        $this->bind(Plugh::class);
    }
}
