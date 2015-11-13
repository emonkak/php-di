<?php

namespace Emonkak\Di\DependencyResolver;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;

class ContainerDependencyResolver implements DependencyResolverInterface
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
     * {@inheritDoc}
     */
    public function getParameterDependency(\ReflectionParameter $parameter)
    {
        $key = $this->injectionPolicy->getParameterKey($parameter);

        if ($parameter->isOptional()) {
            return $this->container->has($key) ? $this->container->resolve($key) : null;
        } else {
            return $this->container->resolve($key);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyDependency(\ReflectionProperty $property)
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
