<?php

namespace Emonkak\Di\Benchmarks;

use Emonkak\Di\Benchmarks\Fixtures\Bar;
use Emonkak\Di\Benchmarks\Fixtures\BarInterface;
use Emonkak\Di\Benchmarks\Fixtures\Baz;
use Emonkak\Di\Benchmarks\Fixtures\BazInterface;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\FooInterface;
use Zend\Di\Di;

/**
 * @Groups({"di"})
 */
class ZendDiBench
{
    public function benchGet()
    {
        $di = new Di();

        $di->instanceManager()
            ->addTypePreference(FooInterface::class, Foo::class)
            ->addTypePreference(BarInterface::class, Bar::class)
            ->addTypePreference(BazInterface::class, Baz::class);

        assert($di->get(Foo::class) instanceof Foo);
    }
}
