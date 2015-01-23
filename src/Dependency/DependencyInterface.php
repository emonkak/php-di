<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\ContainerInterface;

interface DependencyInterface
{
    /**
     * @param DependencyVistorInterface $visitor
     * @return mixed
     */
    public function acceptVisitor(DependencyVistorInterface $visitor);

    /**
     * @param DependencyTraverserInterface $traverser
     * @return mixed
     */
    public function acceptTraverser(DependencyTraverserInterface $traverser);

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
     * @return boolean
     */
    public function isSingleton();
}
