<?php

namespace Emonkak\Di\Dependency;

interface DependencyTraverserInterface
{
    /**
     * @param DependencyInterface $dependency
     * @return mixed
     */
    public function map(DependencyInterface $dependency);
}
