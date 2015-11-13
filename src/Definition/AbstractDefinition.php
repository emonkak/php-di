<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Dependency\DependencyInterface;
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
     * @param ContainerInterface $container
     * @return DependencyInterface
     */
    public function resolveBy(ContainerInterface $container)
    {
        $scope = $this->scope ?: $this->resolveScope($container);

        return $scope->get($this->resolveDependency($container));
    }

    /**
     * @param Container $container
     * @return DependencyInterface
     */
    abstract protected function resolveDependency(ContainerInterface $container);

    /**
     * @param ContainerInterface $container
     * @return ScopeInterface
     */
    abstract protected function resolveScope(ContainerInterface $container);
}
