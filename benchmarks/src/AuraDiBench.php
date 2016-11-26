<?php

namespace Emonkak\Di\Benchmarks;

use Aura\Di\ContainerBuilder;
use Aura\Di\Factory;
use Aura\Di\Config;
use Emonkak\Di\Benchmarks\Fixtures\Foo;

/**
 * @Groups({"di"})
 */
class AuraDiBench
{
    public function benchGet()
    {
        $builder = new ContainerBuilder();
        $container = $builder->newInstance();

        $container->set('Emonkak\Di\Benchmarks\Fixtures\FooInterface', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Foo'));
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Foo']['bar'] = $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Bar');
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Foo']['baz'] = $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Baz');

        $container->set('Emonkak\Di\Benchmarks\Fixtures\BarInterface', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Bar'));
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Bar']['qux'] = $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Qux');
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Bar']['quux'] = $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Quux');

        $container->set('Emonkak\Di\Benchmarks\Fixtures\BazInterface', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Baz'));
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Baz']['corge'] = $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Corge');
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Baz']['grault'] = $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Grault');

        $container->set('Emonkak\Di\Benchmarks\Fixtures\Qux', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Qux'));
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Qux']['garply'] = $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Garply');

        $container->set('Emonkak\Di\Benchmarks\Fixtures\Quux', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Quux'));
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Quux']['waldo'] = $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Waldo');

        $container->set('Emonkak\Di\Benchmarks\Fixtures\Corge', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Corge'));
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Corge']['fred'] = $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Fred');

        $container->set('Emonkak\Di\Benchmarks\Fixtures\Grault', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Grault'));
        $container->params['Emonkak\Di\Benchmarks\Fixtures\Grault']['plugh'] = $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Plugh');

        $container->set('Emonkak\Di\Benchmarks\Fixtures\Garply', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Garply'));
        $container->set('Emonkak\Di\Benchmarks\Fixtures\Waldo', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Waldo'));
        $container->set('Emonkak\Di\Benchmarks\Fixtures\Fred', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Fred'));
        $container->set('Emonkak\Di\Benchmarks\Fixtures\Plugh', $container->lazyNew('Emonkak\Di\Benchmarks\Fixtures\Plugh'));

        assert($container->get('Emonkak\Di\Benchmarks\Fixtures\FooInterface') instanceof Foo);
    }
}
