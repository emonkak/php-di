<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\Scope\ScopeInterface;

abstract class AbstractDefinition implements DefinitionInterface
{
    /**
     * @var ScopeInterface
     */
    private $scope;

    /**
     * @param ScopeInterface
     * @return AbstractDefinition
     */
    public function in(ScopeInterface $scope)
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function resolveBy(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy)
    {
        $scope = $this->scope ?: $this->resolveScope($container, $injectionPolicy);

        return $scope->get($this->resolveDependency($container, $injectionPolicy));
    }

    /**
     * @param Container $container
     * @return DependencyInterface
     */
    abstract protected function resolveDependency(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy);

    /**
     * @param ContainerInterface $container
     * @return ScopeInterface
     */
    abstract protected function resolveScope(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy);
}
