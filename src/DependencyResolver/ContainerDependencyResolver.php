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
     * @param ContainerInterface     $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function getParameterDependency(\ReflectionParameter $parameter)
    {
        $injectionPolicy = $this->container->getInjectionPolicy();
        $key = $injectionPolicy->getParameterKey($parameter);

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
        $injectionPolicy = $this->container->getInjectionPolicy();
        $key = $injectionPolicy->getPropertyKey($property);

        $class = $property->getDeclaringClass();
        $values = $class->getDefaultProperties();

        if (isset($values[$property->name])) {
            return $this->container->has($key) ? $this->container->resolve($key) : null;
        } else {
            return $this->container->resolve($key);
        }
    }
}
