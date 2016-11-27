<?php

namespace Emonkak\Di\Dependency;

use Interop\Container\ContainerInterface;

interface DependencyInterface extends \IteratorAggregate
{
    /**
     * @param DependencyVisitorInterface $visitor
     * @return mixed
     */
    public function accept(DependencyVisitorInterface $visitor);

    /**
     * @return DependencyInterface[]
     */
    public function getDependencies();

    /**
     * @return string
     */
    public function getKey();

    /**
     * @param ContainerInterface $container
     * @param \ArrayAccess       $pool
     * @return mixed
     */
    public function materializeBy(ContainerInterface $container, \ArrayAccess $pool);

    /**
     * @return boolean
     */
    public function isSingleton();
}
