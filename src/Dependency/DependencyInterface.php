<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\ContainerInterface;

interface DependencyInterface
{
    /**
     * @param DependencyVisitorInterface $visitor
     * @return mixed
     */
    public function accept(DependencyVisitorInterface $visitor);

    /**
     * @param ContainerInterface $container
     * @return mixed
     */
    public function inject(ContainerInterface $container);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @return DependencyInterface[]
     */
    public function getDependencies();

    /**
     * @param callable $callback (dependency, key) => ()
     */
     public function traverse(callable $callback);

    /**
     * @return boolean
     */
    public function isSingleton();
}
