<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\Container;
use Emonkak\Di\Scope\ScopeInterface;

class AliasDefinition implements DefinitionInterface
{
    private $target;

    private $scope;

    /**
     * @param string $target
     * @param ScopeInterface $scope
     */
    public function __construct($target, ScopeInterface $scope)
    {
        $this->target = $target;
        $this->scope = $scope;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(Container $container)
    {
        $value = $container->get($this->target);
        return $this->scope->get($value);
    }
}
