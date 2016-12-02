<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\Dependency\DependencyFinders;
use Emonkak\Di\Dependency\ObjectDependency;
use Emonkak\Di\Exception\UninjectableClassException;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\ResolverInterface;
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
    private $constructorInjections;

    /**
     * @var array array(string => DefinitionInterface[])
     */
    private $methodInjections = [];

    /**
     * @var array array(string => DefinitionInterface)
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
     * @return $this
     */
    public function to($target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @param DefinitionInterface[] $injections
     * @return $this
     */
    public function with(array $injections)
    {
        $this->constructorInjections = $injections;
        return $this;
    }

    /**
     * @param string                $method
     * @param DefinitionInterface[] $injections
     * @return $this
     */
    public function withMethod($method, array $injections)
    {
        $this->methodInjections[$method] = $injections;
        return $this;
    }

    /**
     * @param string              $method
     * @param DefinitionInterface $injection
     * @return $this
     */
    public function withProperty($property, DefinitionInterface $injection)
    {
        $this->propertyInjections[$property] = $injection;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveDependency(ResolverInterface $resolver, InjectionPolicyInterface $injectionPolicy)
    {
        $class = new \ReflectionClass($this->target);
        if (!$injectionPolicy->isInjectableClass($class)) {
            throw new UninjectableClassException("Class '$class->name' is not injectable.");
        }

        $constructorDependencies = [];
        if ($this->constructorInjections !== null) {
            foreach ($this->constructorInjections as $definition) {
                $constructorDependencies[] = $definition->resolveBy($resolver, $injectionPolicy);
            }
        } else {
            $constructor = $class->getConstructor();
            if ($constructor !== null) {
                $constructorDependencies = $this->getParameterDependencies($resolver, $constructor);
            }
        }

        $methodDependencies = [];
        foreach ($injectionPolicy->getInjectableMethods($class) as $method) {
            $methodDependencies[$method->name] = $this->getParameterDependencies($resolver, $method);
        }
        foreach ($this->methodInjections as $method => $definitions) {
            $dependencies = [];
            foreach ($definitions as $definition) {
                $dependencies[] = $definition->resolveBy($resolver, $injectionPolicy);
            }
            $methodDependencies[$method] = $dependencies;
        }

        $propertyDependencies = [];
        foreach ($injectionPolicy->getInjectableProperties($class) as $property) {
            $propertyDependencies[$property->name] = $resolver->resolveProperty($property);
        }
        foreach ($this->propertyInjections as $property => $definition) {
            $propertyDependencies[$property] = $definition->resolveBy($resolver, $injectionPolicy);
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
    protected function resolveScope(ResolverInterface $resolver, InjectionPolicyInterface $injectionPolicy)
    {
        $class = new \ReflectionClass($this->target);
        return $injectionPolicy->getScope($class);
    }

    /**
     * @param ResolverInterface           $resolver
     * @param \ReflectionFunctionAbstract $function
     * @return DependencyInterface[]
     */
    private function getParameterDependencies(ResolverInterface $resolver, \ReflectionFunctionAbstract $function)
    {
        $dependencies = [];
        foreach ($function->getParameters() as $parameter) {
            $dependencies[] = $resolver->resolveParameter($parameter);
        }
        return $dependencies;
    }
}
