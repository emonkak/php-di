<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;

interface DefinitionInterface
{
    /**
     * @param ContainerInterface $container
     * @param InjectionPolicyInterface $injectionPolicy
     * @return DependencyInterface
     */
    public function resolveBy(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy);
}
