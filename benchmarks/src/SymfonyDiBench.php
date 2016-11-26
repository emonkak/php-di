<?php

namespace Emonkak\Di\Benchmarks;

use Emonkak\Di\Benchmarks\Fixtures\Foo;
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

        $container->register('Emonkak\Di\Benchmarks\Fixtures\FooInterface', 'Emonkak\Di\Benchmarks\Fixtures\Foo')
            ->addArgument(new Reference('Emonkak\Di\Benchmarks\Fixtures\BarInterface'))
            ->addArgument(new Reference('Emonkak\Di\Benchmarks\Fixtures\BazInterface'));

        $container->register('Emonkak\Di\Benchmarks\Fixtures\BarInterface', 'Emonkak\Di\Benchmarks\Fixtures\Bar')
            ->addArgument(new Reference('Emonkak\Di\Benchmarks\Fixtures\Qux'))
            ->addArgument(new Reference('Emonkak\Di\Benchmarks\Fixtures\Quux'));

        $container->register('Emonkak\Di\Benchmarks\Fixtures\BazInterface', 'Emonkak\Di\Benchmarks\Fixtures\Baz')
            ->addArgument(new Reference('Emonkak\Di\Benchmarks\Fixtures\Corge'))
            ->addArgument(new Reference('Emonkak\Di\Benchmarks\Fixtures\Grault'));

        $container->register('Emonkak\Di\Benchmarks\Fixtures\Qux', 'Emonkak\Di\Benchmarks\Fixtures\Qux')
            ->addArgument(new Reference('Emonkak\Di\Benchmarks\Fixtures\Garply'));
        $container->register('Emonkak\Di\Benchmarks\Fixtures\Quux', 'Emonkak\Di\Benchmarks\Fixtures\Quux')
            ->addArgument(new Reference('Emonkak\Di\Benchmarks\Fixtures\Waldo'));
        $container->register('Emonkak\Di\Benchmarks\Fixtures\Corge', 'Emonkak\Di\Benchmarks\Fixtures\Corge')
            ->addArgument(new Reference('Emonkak\Di\Benchmarks\Fixtures\Fred'));
        $container->register('Emonkak\Di\Benchmarks\Fixtures\Grault', 'Emonkak\Di\Benchmarks\Fixtures\Grault')
            ->addArgument(new Reference('Emonkak\Di\Benchmarks\Fixtures\Plugh'));

        $container->register('Emonkak\Di\Benchmarks\Fixtures\Garply', 'Emonkak\Di\Benchmarks\Fixtures\Garply');
        $container->register('Emonkak\Di\Benchmarks\Fixtures\Waldo', 'Emonkak\Di\Benchmarks\Fixtures\Waldo');
        $container->register('Emonkak\Di\Benchmarks\Fixtures\Fred', 'Emonkak\Di\Benchmarks\Fixtures\Fred');
        $container->register('Emonkak\Di\Benchmarks\Fixtures\Plugh', 'Emonkak\Di\Benchmarks\Fixtures\Plugh');

        assert($container->get('Emonkak\Di\Benchmarks\Fixtures\FooInterface') instanceof Foo);
    }
}
