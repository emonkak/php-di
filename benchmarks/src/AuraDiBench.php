<?php

declare(strict_types=1);

namespace Emonkak\Di\Benchmarks;

use Aura\Di\Config;
use Aura\Di\ContainerBuilder;
use Aura\Di\Factory;
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

/**
 * @Groups({"di"})
 */
class AuraDiBench
{
    public function benchGet()
    {
        $builder = new ContainerBuilder();
        $container = $builder->newInstance();

        $container->set(FooInterface::class, $container->lazyNew(Foo::class));
        $container->params[Foo::class]['bar'] = $container->lazyNew(Bar::class);
        $container->params[Foo::class]['baz'] = $container->lazyNew(Baz::class);

        $container->set(BarInterface::class, $container->lazyNew(Bar::class));
        $container->params[Bar::class]['qux'] = $container->lazyNew(Qux::class);
        $container->params[Bar::class]['quux'] = $container->lazyNew(Quux::class);

        $container->set(BazInterface::class, $container->lazyNew(Baz::class));
        $container->params[Baz::class]['corge'] = $container->lazyNew(Corge::class);
        $container->params[Baz::class]['grault'] = $container->lazyNew(Grault::class);

        $container->set(Qux::class, $container->lazyNew(Qux::class));
        $container->params[Qux::class]['garply'] = $container->lazyNew(Garply::class);

        $container->set(Quux::class, $container->lazyNew(Quux::class));
        $container->params[Quux::class]['waldo'] = $container->lazyNew(Waldo::class);

        $container->set(Corge::class, $container->lazyNew(Corge::class));
        $container->params[Corge::class]['fred'] = $container->lazyNew(Fred::class);

        $container->set(Grault::class, $container->lazyNew(Grault::class));
        $container->params[Grault::class]['plugh'] = $container->lazyNew(Plugh::class);

        $container->set(Garply::class, $container->lazyNew(Garply::class));
        $container->set(Waldo::class, $container->lazyNew(Waldo::class));
        $container->set(Fred::class, $container->lazyNew(Fred::class));
        $container->set(Plugh::class, $container->lazyNew(Plugh::class));

        assert($container->get(FooInterface::class) instanceof Foo);
    }
}
