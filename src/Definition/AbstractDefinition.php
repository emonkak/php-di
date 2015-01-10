<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\Container;
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
     * @param Container $container
     * @return InjectableValueInterface
     */
    public function get(Container $container)
    {
        $scope = $this->scope ?: $this->resolveScope($container);

        return $scope->get($this->resolve($container));
    }

    /**
     * @param Container $container
     * @return InjectableValueInterface
     */
    abstract protected function resolve(Container $container);

    /**
     * @param Container $container
     * @return ScopeInterface
     */
    abstract protected function resolveScope(Container $container);
}
