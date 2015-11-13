<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
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
    private $constructorParameters;

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
        $this->constructorParameters = $parameters;
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
    protected function resolveDependency(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy)
    {
        $class = new \ReflectionClass($this->target);

        if (!$injectionPolicy->isInjectableClass($class)) {
            throw new \LogicException(
                sprintf('Class "%s" is not injectable.', $class->name)
            );
        }

        $injectionFinder = $container->getInjectionFinder();

        if ($this->constructorParameters !== null) {
            $constructorDependencies = [];
            foreach ($this->constructorParameters as $definition) {
                $constructorDependencies[] = $definition->resolveBy($container, $injectionPolicy);
            }
        } else {
            $constructorDependencies = $injectionFinder->getConstructorDependencies($class);
        }

        $methodDependencies = $injectionFinder->getMethodDependencies($class);
        foreach ($this->methodInjections as $method => $definitions) {
            $dependencies = [];
            foreach ($definitions as $definition) {
                $dependencies[] = $definition->resolveBy($container, $injectionPolicy);
            }
            $methodDependencies[$method] = $dependencies;
        }

        $propertyDependencies = $injectionFinder->getPropertyDependencies($class);
        foreach ($this->propertyInjections as $property => $definition) {
            $propertyDependencies[$property] = $definition->resolveBy($container, $injectionPolicy);
        }

        return new ObjectDependency(
            $this->key,
            $class->name,
            $constructorDependencies,
            $methodDependencies,
            $propertyDependencies
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveScope(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy)
    {
        $class = new \ReflectionClass($this->target);

        return $injectionPolicy->getScope($class);
    }
}
