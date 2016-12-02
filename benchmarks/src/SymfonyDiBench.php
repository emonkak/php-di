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
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @Groups({"di"})
 */
class SymfonyDiBench
{
    public function benchGet()
    {
        $container = new ContainerBuilder();

        $container->register(FooInterface::class, Foo::class)
            ->addArgument(new Reference(BarInterface::class))
            ->addArgument(new Reference(BazInterface::class));

        $container->register(BarInterface::class, Bar::class)
            ->addArgument(new Reference(Qux::class))
            ->addArgument(new Reference(Quux::class));

        $container->register(BazInterface::class, Baz::class)
            ->addArgument(new Reference(Corge::class))
            ->addArgument(new Reference(Grault::class));

        $container->register(Qux::class, Qux::class)
            ->addArgument(new Reference(Garply::class));
        $container->register(Quux::class, Quux::class)
            ->addArgument(new Reference(Waldo::class));
        $container->register(Corge::class, Corge::class)
            ->addArgument(new Reference(Fred::class));
        $container->register(Grault::class, Grault::class)
            ->addArgument(new Reference(Plugh::class));

        $container->register(Garply::class, Garply::class);
        $container->register(Waldo::class, Waldo::class);
        $container->register(Fred::class, Fred::class);
        $container->register(Plugh::class, Plugh::class);

        assert($container->get(FooInterface::class) instanceof Foo);
    }
}
