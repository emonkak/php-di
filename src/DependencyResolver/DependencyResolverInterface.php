<?php

namespace Emonkak\Di\DependencyResolver;

use Emonkak\Di\Dependency\DependencyInterface;

interface DependencyResolverInterface
{
    /**
     * @param \ReflectionParameter $param
     * @return DependencyInterface
     */
    public function getParameterDependency(\ReflectionParameter $param);

    /**
     * @param \ReflectionProperty $property
     * @return DependencyInterface
     */
    public function getPropertyDependency(\ReflectionProperty $property);
}
