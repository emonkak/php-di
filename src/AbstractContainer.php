<?php

namespace Emonkak\Di;

use Emonkak\Di\Definition\AliasDefinition;
use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\Definition\DefinitionInterface;
use Emonkak\Di\Definition\FactoryDefinition;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\Exception\NotFoundException;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;

abstract class AbstractContainer implements ContainerInterface
{
    /**
     * @var InjectionFinder
     */
    private $injectionFinder;

    /**
     * @var InjectionPolicyInterface
     */
    private $injectionPolicy;

    /**
     * @var DefinitionInterface[]
     */
    private $definitions = [];

    /**
     * @var \ArrayAccess
     */
    private $cache;

    /**
     * @param InjectionPolicyInterface $injectionPolicy
     * @param \ArrayAccess             $cache
     */
    public function __construct(InjectionPolicyInterface $injectionPolicy, \ArrayAccess $cache)
    {
        $this->injectionFinder = new InjectionFinder($this, $injectionPolicy);
        $this->injectionPolicy = $injectionPolicy;
        $this->cache = $cache;
    }

    /**
     * {@inheritDoc}
     */
    public function getInjectionFinder()
    {
        return $this->injectionFinder;
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
        $this->setValue($key, $value);
        return $this->definitions[$key] = new ReferenceDependency($key);
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
                throw new NotFoundException(
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
    public function has($key)
    {
        return isset($this->cache[$key]) || isset($this->definitions[$key]) || class_exists($key);
    }
}
