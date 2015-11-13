<?php

namespace Emonkak\Di;

use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;

class InjectionFinder
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var InjectionPolicyInterface
     */
    private $injectionPolicy;

    /**
     * @param ContainerInterface       $container
     * @param InjectionPolicyInterface $injectionPolicy
     */
    public function __construct(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy)
    {
        $this->container = $container;
        $this->injectionPolicy = $injectionPolicy;
    }

    /**
     * @param \ReflectionClass $class
     * @return DependencyInterface[]
     */
    public function getConstructorDependencies(\ReflectionClass $class)
    {
        $constructor = $class->getConstructor();
        return $constructor ? $this->getParameterDependencies($constructor) : [];
    }

    /**
     * @param \ReflectionClass $class
     * @return DependencyInterface[]
     */
    public function getMethodDependencies(\ReflectionClass $class)
    {
        $injections = [];

        $methods = $this->injectionPolicy->getInjectableMethods($class);
        foreach ($methods as $method) {
            $injections[$method->name] = $this->getParameterDependencies($method);
        }

        return $injections;
    }

    /**
     * @param \ReflectionClass $class
     * @return DependencyInterface[]
     */
    public function getPropertyDependencies(\ReflectionClass $class)
    {
        $injections = [];

        $properties = $this->injectionPolicy->getInjectableProperties($class);
        foreach ($properties as $property) {
            $dependency = $this->resolvePropertyDependency($property);
            if ($dependency) {
                $injections[$property->name] = $dependency;
            }
        }

        return $injections;
    }

    /**
     * @param \ReflectionFunctionAbstract $function
     * @return DependencyInterface[]
     */
    public function getParameterDependencies(\ReflectionFunctionAbstract $function)
    {
        $dependencies = [];
        foreach ($function->getParameters() as $param) {
            $dependency = $this->resolveParameterDependency($param);
            if ($dependency) {
                $dependencies[] = $dependency;
            }
        }
        return $dependencies;
    }

    /**
     * @param \ReflectionParameter $param
     * @return DependencyInterface
     */
    public function resolveParameterDependency(\ReflectionParameter $parameter)
    {
        $key = $this->injectionPolicy->getParameterKey($parameter);

        if ($parameter->isOptional()) {
            return $this->container->has($key) ? $this->container->resolve($key) : null;
        } else {
            return $this->container->resolve($key);
        }
    }

    /**
     * @param \ReflectionProperty $property
     * @return DependencyInterface
     */
    public function resolvePropertyDependency(\ReflectionProperty $property)
    {
        $key = $this->injectionPolicy->getPropertyKey($property);

        $class = $property->getDeclaringClass();
        $values = $class->getDefaultProperties();

        if (isset($values[$property->name])) {
            return $this->container->has($key) ? $this->container->resolve($key) : null;
        } else {
            return $this->container->resolve($key);
        }
    }
}
