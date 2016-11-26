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
            ->bind('Emonkak\Di\Tests\Stubs\BarInterface')
            ->to('Emonkak\Di\Tests\Stubs\Bar');
        $container
            ->bind('Emonkak\Di\Tests\Stubs\BazInterface')
            ->to('Emonkak\Di\Tests\Stubs\Baz')
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
