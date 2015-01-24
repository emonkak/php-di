<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Dependency\DependencyInterface;

interface DefinitionInterface
{
    /**
     * @param ContainerInterface $container
     * @return DependencyInterface
     */
    public function get(ContainerInterface $container);
}
