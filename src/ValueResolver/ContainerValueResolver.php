<?php

namespace Emonkak\Di\ValueResolver;

use Emonkak\Di\Container;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;

class ContainerValueResolver implements ValueResolverInterface
{
    private $container;
    private $injectionPolicy;

    /**
     * @param Container                $container
     * @param InjectionPolicyInterface $injectionPolicy
     */
    public function __construct(Container $container, InjectionPolicyInterface $injectionPolicy)
    {
        $this->container = $container;
        $this->injectionPolicy = $injectionPolicy;
    }

    /**
     * {@inheritDoc}
     */
    public function getParameterValue(\ReflectionParameter $param)
    {
        $key = $this->injectionPolicy->getParameterKey($param);
        return $this->container->has($key) ? $this->container->get($key) : null;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyValue(\ReflectionProperty $property)
    {
        $key = $this->injectionPolicy->getPropertyKey($property);
        return $this->container->has($key) ? $this->container->get($key) : null;
    }
}
