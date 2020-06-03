<?php

declare(strict_types=1);

namespace Emonkak\Di\Benchmarks;

use DI\ContainerBuilder;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ApcuCache;
use Emonkak\Di\Benchmarks\Fixtures\Foo;
use Emonkak\Di\Benchmarks\Fixtures\FooInterface;

/**
 * @Groups({"di"})
 */
class PhpDiBench
{
    public function benchGet()
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__ . '/phpdi-config.php');
        $container = $builder->build();
        assert($container->get(FooInterface::class) instanceof Foo);
    }

    public function benchGetWithCache()
    {
        $builder = new ContainerBuilder();
        $builder->enableCompilation(__DIR__ . '/../.cache');
        $builder->writeProxiesToFile(true, __DIR__ . '/../.cache/proxies');
        $builder->addDefinitions(__DIR__ . '/phpdi-config.php');
        $container = $builder->build();
        assert($container->get(Foo::class) instanceof Foo);
    }
}
