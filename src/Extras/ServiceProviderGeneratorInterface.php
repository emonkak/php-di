<?php

namespace Emonkak\Di\Extras;

use Emonkak\Di\Dependency\DependencyInterface;

interface ServiceProviderGeneratorInterface
{
    /**
     * @param string              $className
     * @param DependencyInterface $dependency
     * @return string
     */
    public function generate($className, DependencyInterface $dependency);
}
