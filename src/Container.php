<?php

namespace Emonkak\Di;

use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\ValueDependency;
use Emonkak\Di\Exception\KeyNotFoundException;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;

class Container extends Module implements ResolverInterface, ContainerInterface
{
    /**
     * @var InjectionPolicyInterface
     */
    protected $injectionPolicy;

    /**
     * @var \ArrayAccess
     */
    protected $cache;

    /**
     * @param InjectionPolicyInterface $injectionPolicy
     * @param \ArrayAccess             $cache
     * @return Container
     */
    public static function create(
        InjectionPolicyInterface $injectionPolicy = null,
        \ArrayAccess $cache = null
    ) {
        return new Container(
            $injectionPolicy ?: new DefaultInjectionPolicy(),
            $cache ?: new \ArrayObject()
        );
    }

    /**
     * @param InjectionPolicyInterface $injectionPolicy
     * @param \ArrayAccess             $cache
     */
    public function __construct(InjectionPolicyInterface $injectionPolicy, \ArrayAccess $cache)
    {
        $this->injectionPolicy = $injectionPolicy;
        $this->cache = $cache;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }

        return $this->resolve($key)->instantiateBy($this);
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        return isset($this->definitions[$key]) || isset($this->values[$key]) || isset($this->cache[$key]) || class_exists($key);
    }

    /**
     * {@inheritDoc}
     */
    public function store($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function isStored($key)
    {
        return isset($this->values[$key]);
    }

    /**
     * {@inheritDoc}
     */
    public function resolve($key)
    {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        if (isset($this->definitions[$key])) {
            $definition = $this->definitions[$key];
        } else {
            if (!class_exists($key)) {
                throw new KeyNotFoundException(
                    sprintf('Key "%s" does not registered in this container.', $key)
                );
            }
            $definition = new BindingDefinition($key);
        }

        $dependency = $definition->resolveBy($this, $this->injectionPolicy);
        $this->cache[$key] = $dependency;

        return $dependency;
    }

    /**
     * {@inheritDoc}
     */
    public function resolveParameter(\ReflectionParameter $parameter)
    {
        $key = $this->injectionPolicy->getParameterKey($parameter);
        try {
            return $this->resolve($key);
        } catch (KeyNotFoundException $e) {
            if (!$parameter->isOptional()) {
                throw KeyNotFoundException::unresolvedParameter($key, $parameter, $e);
            }
            $defaultValue = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null;
            return new ValueDependency($key, $defaultValue);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function resolveProperty(\ReflectionProperty $property)
    {
        $key = $this->injectionPolicy->getPropertyKey($property);
        try {
            return $this->resolve($key);
        } catch (KeyNotFoundException $e) {
            $class = $property->getDeclaringClass();
            $values = $class->getDefaultProperties();
            if (!isset($values[$property->name])) {
                // XXX: Throws an exception even if the default value is null.
                throw KeyNotFoundException::unresolvedProperty($key, $property, $e);
            }
            return new ValueDependency($key, $values[$property->name]);
        }
    }
}
