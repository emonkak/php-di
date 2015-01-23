<?php

namespace Emonkak\Di;

use Doctrine\Common\Cache\Cache;
use Emonkak\Di\Definition\AliasDefinition;
use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\Definition\DefinitionInterface;
use Emonkak\Di\Definition\FactoryDefinition;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\ValueResolver\ContainerValueResolver;
use Emonkak\Di\ValueResolver\DefaultValueResolver;
use Emonkak\Di\ValueResolver\ValueResolverInterface;

abstract class AbstractContainer implements ContainerInterface
{
    /**
     * @var ValueResolverInterface
     */
    private $valueResolver;

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
     * @var Cache
     */
    private $cache;

    /**
     * @param InjectionPolicyInterface $injectionPolicy
     * @param Cache                    $cache
     */
    public function __construct(InjectionPolicyInterface $injectionPolicy, Cache $cache)
    {
        $this->valueResolver = new ContainerValueResolver($this, new DefaultValueResolver());
        $this->injectionFinder = new InjectionFinder($this->valueResolver, $injectionPolicy);
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
        if ($this->cache->contains($key)) {
            return $this->cache->fetch($key);
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
        $this->cache->save($key, $dependency);

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
        return $this->cache->contains($key) || isset($this->definitions[$key]) || class_exists($key);
    }
}
