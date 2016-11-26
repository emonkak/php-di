<?php

namespace Emonkak\Di\Benchmarks;

use DI;
use DI\ContainerBuilder;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ApcuCache;
use Emonkak\Di\Benchmarks\Fixtures\Foo;

/**
 * @Groups({"di"})
 */
class PhpDiBench
{
    public function benchGet()
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__ . '/phpdi.php');
        $container = $builder->build();
        assert($container->get('Emonkak\Di\Benchmarks\Fixtures\FooInterface') instanceof Foo);
    }

    public function benchGetWithCache()
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__ . '/phpdi.php');
        $builder->setDefinitionCache(extension_loaded('apcu') ? new ApcuCache() : new ApcCache());
        $cachedContainer = $builder->build();
        assert($cachedContainer->get('Emonkak\Di\Benchmarks\Fixtures\Foo') instanceof Foo);
    }
}
