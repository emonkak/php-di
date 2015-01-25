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
     * @return \Iterator
     */
    public function getDependencies();

    /**
     * @return \Iterator
     */
     public function enumerate();

    /**
     * @return boolean
     */
    public function isSingleton();
}
