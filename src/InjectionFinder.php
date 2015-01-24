<?php

namespace Emonkak\Di;

use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\DependencyResolver\DependencyResolverInterface;

class InjectionFinder
{
    /**
     * @var DependencyResolverInterface
     */
    private $dependencyResolver;

    /**
     * @var InjectionPolicyInterface
     */
    private $injectionPolicy;

    /**
     * @param DependencyResolverInterface $dependencyResolver
     * @param InjectionPolicyInterface    $injectionPolicy
     */
    public function __construct(DependencyResolverInterface $dependencyResolver, InjectionPolicyInterface $injectionPolicy)
    {
        $this->dependencyResolver = $dependencyResolver;
        $this->injectionPolicy = $injectionPolicy;
    }

    /**
     * @param \ReflectionClass $class
     * @return DependencyInterface[]
     */
    public function getConstructorParameters(\ReflectionClass $class)
    {
        $constructor = $class->getConstructor();
        return $constructor ? $this->getParameterDependencies($constructor) : [];
    }

    /**
     * @param \ReflectionClass $class
     * @return DependencyInterface[]
     */
    public function getMethodInjections(\ReflectionClass $class)
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
    public function getPropertyInjections(\ReflectionClass $class)
    {
        $injections = [];

        $properties = $this->injectionPolicy->getInjectableProperties($class);
        foreach ($properties as $property) {
            $dependency = $this->dependencyResolver->getPropertyDependency($property);
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
            $dependency = $this->dependencyResolver->getParameterDependency($param);
            if ($dependency) {
                $dependencies[] = $dependency;
            }
        }
        return $dependencies;
    }
}
