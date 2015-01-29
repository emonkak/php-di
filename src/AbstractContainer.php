<?php

namespace Emonkak\Di;

use Emonkak\Di\Definition\AliasDefinition;
use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\Definition\DefinitionInterface;
use Emonkak\Di\Definition\FactoryDefinition;
use Emonkak\Di\DependencyResolver\ContainerDependencyResolver;
use Emonkak\Di\DependencyResolver\DependencyResolverInterface;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;

abstract class AbstractContainer implements ContainerInterface
{
    /**
     * @var DependencyResolverInterface
     */
    private $dependencyResolver;

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
        $this->dependencyResolver = new ContainerDependencyResolver($this);
        $this->injectionFinder = new InjectionFinder($this->dependencyResolver, $injectionPolicy);
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
     * {@inheritDoc}
     */
    public function getInjectionPolicy()
    {
        return $this->injectionPolicy;
    }

    /**
     * {@inheritDoc}
     */
    public function configure(ContainerConfiguratorInterface $configurator)
    {
        $configurator->configure($this);
    }

    /**
     * {@inheritDoc}
     */
    public function alias($key, $target)
    {
        return $this->definitions[$key] = new AliasDefinition($target);
    }

    /**
     * {@inheritDoc}
     */
    public function bind($target)
    {
        return $this->definitions[$target] = new BindingDefinition($target);
    }

    /**
     * {@inheritDoc}
     */
    public function factory($key, callable $target)
    {
        return $this->definitions[$key] = new FactoryDefinition($key, $target);
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        if (isset($this->definitions[$key])) {
            $definition = $this->definitions[$key];
        } else {
            if (!class_exists($key)) {
                throw new \InvalidArgumentException(
                    sprintf('Key "%s" does not registered in this container.', $key)
                );
            }
            $definition = new BindingDefinition($key);
        }

        $dependency = $definition->get($this);
        $this->cache[$key] = $dependency;

        return $dependency;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $this->setInstance($key, $value);
        return $this->definitions[$key] = new ReferenceDependency($key);
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        return isset($this->cache) || isset($this->definitions[$key]) || class_exists($key);
    }
}
