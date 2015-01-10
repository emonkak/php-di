<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\Container;
use Emonkak\Di\Scope\ScopeInterface;
use Emonkak\Di\Value\PrototypeValue;

class BindingDefinition implements DefinitionInterface
{
    private $class;

    private $scope;

    /**
     * @param \ReflectionClass $class
     * @param ScopeInterface   $scope
     */
    public function __construct(\ReflectionClass $class, ScopeInterface $scope)
    {
        $this->class = $class;
        $this->scope = $scope;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(Container $container)
    {
        $injectionFinder = $container->getInjectionFinder();
        $constructorInjection = $injectionFinder->getConstructorInjection($this->class);
        $methodInjections = $injectionFinder->getMethodInjections($this->class);
        $propertyInjections = $injectionFinder->getPropertyInjections($this->class);
        $value = new PrototypeValue(
            $this->class,
            $constructorInjection,
            $methodInjections,
            $propertyInjections
        );
        return $this->scope->get($value);
    }
}
