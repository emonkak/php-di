<?php

namespace Emonkak\Di;

use Emonkak\Di\Dependency\DependencyInterface;

interface ResolverInterface
{
    /**
     * @param string $key
     * @return DependencyInterface
     */
    public function resolve($key);

    /**
     * @param \ReflectionParameter $parameter
     * @return DependencyInterface
     */
    public function resolveParameter(\ReflectionParameter $parameter);

    /**
     * @param \ReflectionProperty $property
     * @return DependencyInterface
     */
    public function resolveProperty(\ReflectionProperty $property);
}
