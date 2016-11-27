<?php

namespace Emonkak\Di;

use Emonkak\Di\Definition\AliasDefinition;
use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\Definition\DefinitionInterface;
use Emonkak\Di\Definition\FactoryDefinition;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\Dependency\ValueDependency;
use Emonkak\Di\Exception\KeyNotFoundException;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Interop\Container\ContainerInterface;

abstract class AbstractContainer implements ContainerInterface, ResolverInterface
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
     * @var \ArrayAccess
     */
    protected $pool;

    /**
     * @var DefinitionInterface[]
     */
    protected $definitions = [];

    /**
     * @param InjectionPolicyInterface $injectionPolicy
     * @param \ArrayAccess             $cache
     * @param \ArrayAccess             $pool
     */
    public function __construct(InjectionPolicyInterface $injectionPolicy, \ArrayAccess $cache, \ArrayAccess $pool)
    {
        $this->injectionPolicy = $injectionPolicy;
        $this->cache = $cache;
        $this->pool = $pool;
    }

    /**
     * @param ContainerConfiguratorInterface $configurator
     */
    public function configure(ContainerConfiguratorInterface $configurator)
    {
        $configurator->configure($this);
    }

    /**
     * @param string $key
     * @param string $target
     * @return AliasDefinition
     */
    public function alias($key, $target)
    {
        return $this->definitions[$key] = new AliasDefinition($target);
    }

    /**
     * @param string $target
     * @return BindingDefinition
     */
    public function bind($target)
    {
        return $this->definitions[$target] = new BindingDefinition($target);
    }

    /**
     * @param string   $key
     * @param callable $target
     * @return FactoryDefinition
     */
    public function factory($key, callable $target)
    {
        return $this->definitions[$key] = new FactoryDefinition($key, $target);
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @return ReferenceDependency
     */
    public function set($key, $value)
    {
        $this->pool[$key] = $value;
        return $this->definitions[$key] = new ReferenceDependency($key);
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        return isset($this->definitions[$key]) || isset($this->pool[$key]) || isset($this->cache[$key]) || class_exists($key);
    }

    /**
     * @param DependencyInterface $dependency
     * @return mixed
     */
    public function instantiate(DependencyInterface $dependency)
    {
        return $dependency->instantiateBy($this, $this->pool);
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
                throw KeyNotFoundException::fromParameter($key, $parameter, $e);
            }
            $defaultValue = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null;
            return new ValueDependency($defaultValue);
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
            if (!array_key_exists($property->name, $values)) {
                throw KeyNotFoundException::fromProperty($key, $property, $e);
            }
            return new ValueDependency($values[$property->name]);
        }
    }
}
