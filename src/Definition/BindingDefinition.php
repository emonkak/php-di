<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Scope\ScopeInterface;

class BindingDefinition extends AbstractDefinition
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $target;

    /**
     * @var DefinitionInterface[]
     */
    private $constructorParamerters;

    /**
     * @var DefinitionInterface[] array(methodName => paramerters)
     */
    private $methodInjections = [];

    /**
     * @var DefinitionInterface[] array(propertyName => value)
     */
    private $propertyInjections = [];

    /**
     * @param string $target
     */
    public function __construct($target)
    {
        $this->key = $target;
        $this->target = $target;
    }

    /**
     * @param string $target
     */
    public function to($target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @param DefinitionInterface[] $parameters
     * @return BindingDefinition
     */
    public function with(array $parameters)
    {
        $this->constructorParamerters = $parameters;
        return $this;
    }

    /**
     * @param string                $method
     * @param DefinitionInterface[] $parameters
     * @return BindingDefinition
     */
    public function withMethod($method, array $parameters)
    {
        $this->methodInjections[$method] = $parameters;
        return $this;
    }

    /**
     * @param string                $method
     * @param DefinitionInterface[] $parameters
     * @return BindingDefinition
     */
    public function withProperty($property, DefinitionInterface $value)
    {
        $this->propertyInjections[$property] = $value;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function resolve(ContainerInterface $container)
    {
        $class = new \ReflectionClass($this->target);
        $injectionPolicy = $container->getInjectionPolicy();

        if (!$injectionPolicy->isInjectableClass($class)) {
            throw new \LogicException(
                sprintf('Class "%s" is not injectable.', $class->name)
            );
        }

        $injectionFinder = $container->getInjectionFinder();

        if ($this->constructorParamerters !== null) {
            $constructorParamerters = [];
            foreach ($this->constructorParamerters as $definition) {
                $constructorParamerters[] = $definition->get($container);
            }
        } else {
            $constructorParamerters = $injectionFinder->getConstructorParameters($class);
        }

        $methodInjections = $injectionFinder->getMethodInjections($class);
        foreach ($this->methodInjections as $method => $definitions) {
            $parameters = [];
            foreach ($definitions as $definition) {
                $parameters[] = $definition->get($container);
            }
            $methodInjections[$method] = $parameters;
        }

        $propertyInjections = $injectionFinder->getPropertyInjections($class);
        foreach ($this->propertyInjections as $property => $definition) {
            $propertyInjections[$property] = $definition->get($container);
        }

        return new ObjectDependency(
            $this->key,
            $class->name,
            $constructorParamerters,
            $methodInjections,
            $propertyInjections
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveScope(ContainerInterface $container)
    {
        $class = new \ReflectionClass($this->target);
        $injectionPolicy = $container->getInjectionPolicy();

        return $injectionPolicy->getScope($class);
    }
}
