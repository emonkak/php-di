<?php

namespace Emonkak\Di\Scope;

use Emonkak\Di\Dependency\DependencyInterface;

interface ScopeInterface
{
    /**
     * @param DependencyInterface $dependency
     * @return DependencyInterface
     */
    public function get(DependencyInterface $dependency);
}
