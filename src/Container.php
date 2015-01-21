<?php

namespace Emonkak\Di;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Emonkak\Di\Definition\AliasDefinition;
use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\Definition\DefinitionInterface;
use Emonkak\Di\Definition\FactoryDefinition;
use Emonkak\Di\Dependency\DependencyInterface;
use Emonkak\Di\Dependency\ReferenceDependency;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\Scope\PrototypeScope;
use Emonkak\Di\Scope\ScopeInterface;
use Emonkak\Di\ValueResolver\ChainValueResolver;
use Emonkak\Di\ValueResolver\ContainerValueResolver;
use Emonkak\Di\ValueResolver\DefaultValueResolver;
use Emonkak\Di\ValueResolver\ValueResolverInterface;

class Container
{
    /**
     * @var ChainValueResolver
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
     * @var \ArrayAccess
     */
    private $valueBag;

    /**
     * @return Container
     */
    public static function create()
    {
        return new Container(new DefaultInjectionPolicy(), new ArrayCache(), new \ArrayObject());
    }

    /**
     * @param InjectionPolicyInterface $injectionPolicy
     * @param Cache                    $cache
     * @param \ArrayAccess             $valueBag
     */
    public function __construct(InjectionPolicyInterface $injectionPolicy, Cache $cache, \ArrayAccess $valueBag)
    {
        $this->valueResolver = new ContainerValueResolver($this, new DefaultValueResolver());
        $this->injectionFinder = new InjectionFinder($this->valueResolver, $injectionPolicy);
        $this->injectionPolicy = $injectionPolicy;
        $this->cache = $cache;
        $this->valueBag = $valueBag;
    }

    /**
     * @return InjectionFinder
     */
    public function getInjectionFinder()
    {
        return $this->injectionFinder;
    }

    /**
     * @return InjectionPolicy
     */
    public function getInjectionPolicy()
    {
        return $this->injectionPolicy;
    }

    /**
     * @param ContainerConfiguratorInterface $configurator
     */
    public function configure(ContainerConfiguratorInterface $configurator)
    {
        $configurator->configure($this);
    }

    /**
     * @param string         $key
     * @param string         $target
     * @param ScopeInterface $scope
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
        return $this->definitions[$key] = new FactoryDefinition($target);
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @return Container
     */
    public function set($key, $value)
    {
        $this->valueBag[$key] = $value;
        $this->definitions[$key] = new ReferenceDependency($key);
    }

    /**
     * @param string $key
     * @return DependencyInterface
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
     * @param string $key
     * @return mixed
     */
    public function getInstance($key)
    {
        return $this->get($key)->inject($this->valueBag);
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return $this->cache->contains($key) || isset($this->definitions[$key]) || class_exists($key);
    }
}
