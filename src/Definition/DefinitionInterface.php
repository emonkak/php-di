<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\ResolverInterface;

interface DefinitionInterface
{
    /**
     * @param ResolverInterface $resolver
     * @param InjectionPolicyInterface $injectionPolicy
     * @return DependencyInterface
     */
    public function resolveBy(ResolverInterface $resolver, InjectionPolicyInterface $injectionPolicy);
}
