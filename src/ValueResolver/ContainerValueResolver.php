<?php

namespace Emonkak\Di\ValueResolver;

use Emonkak\Di\Container;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;

class ContainerValueResolver implements ValueResolverInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var InjectionPolicy
     */
    private $injectionPolicy;

    /**
     * @var ValueResolverInterface
     */
    private $fallback;

    /**
     * @param Container              $container
     * @param ValueResolverInterface $fallback
     */
    public function __construct(Container $container, ValueResolverInterface $fallback)
    {
        $this->container = $container;
        $this->fallback = $fallback;
    }

    /**
     * {@inheritDoc}
     */
    public function getParameterValue(\ReflectionParameter $parameter)
    {
        $injectionPolicy = $this->container->getInjectionPolicy();
        $key = $injectionPolicy->getParameterKey($parameter);

        if ($this->container->has($key)) {
            return $this->container->get($key);
        }

        return $this->fallback->getParameterValue($parameter);
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyValue(\ReflectionProperty $property)
    {
        $injectionPolicy = $this->container->getInjectionPolicy();
        $key = $injectionPolicy->getPropertyKey($property);

        if ($this->container->has($key)) {
            return $this->container->get($key);
        }

        return $this->fallback->getPropertyKey($property);
    }
}
