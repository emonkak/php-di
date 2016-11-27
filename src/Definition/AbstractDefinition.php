<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\ResolverInterface;
use Emonkak\Di\Scope\ScopeInterface;

abstract class AbstractDefinition implements DefinitionInterface
{
    /**
     * @var ScopeInterface
     */
    private $scope;

    /**
     * @param ScopeInterface
     * @return $this
     */
    public function in(ScopeInterface $scope)
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function resolveBy(ResolverInterface $resolver, InjectionPolicyInterface $injectionPolicy)
    {
        $dependency = $this->resolveDependency($resolver, $injectionPolicy);
        $scope = $this->scope ?: $this->resolveScope($resolver, $injectionPolicy);
        return $scope->get($dependency);
    }

    /**
     * @param ResolverInterface        $resolver
     * @param InjectionPolicyInterface $injectionPolicy
     * @return DependencyInterface
     */
    abstract protected function resolveDependency(ResolverInterface $resolver, InjectionPolicyInterface $injectionPolicy);

    /**
     * @param ResolverInterface        $resolver
     * @param InjectionPolicyInterface $injectionPolicy
     * @return ScopeInterface
     */
    abstract protected function resolveScope(ResolverInterface $resolver, InjectionPolicyInterface $injectionPolicy);
}
