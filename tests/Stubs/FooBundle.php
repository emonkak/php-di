<?php

namespace Emonkak\Di\Tests\Stubs;

use Emonkak\Di\AbstractContainer;
use Emonkak\Di\ContainerConfiguratorInterface;
use Emonkak\Di\Scope\SingletonScope;

class FooBundle implements ContainerConfiguratorInterface
{
    public function configure(AbstractContainer $container)
    {
        $container
            ->bind(BarInterface::class)
            ->to(Bar::class);
        $container
            ->bind(BazInterface::class)
            ->to(Baz::class)
            ->in(SingletonScope::getInstance());
        $container->alias('$piyo', '$payo');
        $container->set('$payo', 'payo');
        $container->factory('$poyo', function() {
            return 'poyo';
        });
        $container->set('$hoge', function() {
            return 'hoge';
        });
    }
}
