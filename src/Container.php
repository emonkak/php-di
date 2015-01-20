<?php

namespace Emonkak\Di;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Emonkak\Di\Definition\AliasDefinition;
use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\Definition\DefinitionInterface;
use Emonkak\Di\Definition\FactoryDefinition;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\Scope\PrototypeScope;
use Emonkak\Di\Scope\ScopeInterface;
use Emonkak\Di\ValueResolver\ChainValueResolver;
use Emonkak\Di\ValueResolver\ContainerValueResolver;
use Emonkak\Di\ValueResolver\DefaultValueResolver;
use Emonkak\Di\ValueResolver\ValueResolverInterface;
use Emonkak\Di\Value\ImmediateValue;
use Emonkak\Di\Value\InjectableValueInterface;
use Emonkak\Di\Value\UndefinedValue;

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
     * @var SplObjectStorage
     */
    private $keys;

    /**
     * @return Container
     */
    public static function create()
    {
        return new Container(new DefaultInjectionPolicy(), new ArrayCache());
    }

    /**
     * @param InjectionPolicyInterface $injectionPolicy
     * @param Cache                    $cache
     */
    public function __construct(InjectionPolicyInterface $injectionPolicy, Cache $cache)
    {
        $this->valueResolver = new ChainValueResolver([
            new ContainerValueResolver($this, $injectionPolicy),
            new DefaultValueResolver()
        ]);
        $this->injectionFinder = new InjectionFinder($this->valueResolver, $injectionPolicy);
        $this->injectionPolicy = $injectionPolicy;
        $this->cache = $cache;
        $this->keys = new \SplObjectStorage();
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
     * @param ValueResolverInterface $valueResolver
     * @return Container
     */
    public function appendValueResolver(ValueResolverInterface $valueResolver)
    {
        $this->valueResolver->append($valueResolver);
        return $this;
    }

    /**
     * @param ValueResolverInterface $valueResolver
     * @return Container
     */
    public function prependValueResolver(ValueResolverInterface $valueResolver)
    {
        $this->valueResolver->prepend($valueResolver);
        return $this;
    }

    /**
     * @param string $key
     * @param string $target
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
        $this->setValue($key, new ImmediateValue($value));
        return $this;
    }

    /**
     * @param string $key
     * @param InjectableValueInterface $value
     * @return Container
     */
    public function setValue($key, InjectableValueInterface $value)
    {
        $this->cache->save($key, $value);
        $this->keys[$value] = $key;
        return $this;
    }

    /**
     * @param string $key
     * @return Container
     */
    public function undefined($key)
    {
        $this->setValue($key, new UndefinedValue());
        return $this;
    }

    /**
     * @param string $key
     * @return InjectableValueInterface
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

        $value = $definition->get($this);
        $this->setValue($key, $value);
        return $value;
    }

    /**
     * @param InjectableValueInterface $value
     * @return string
     */
    public function getKey(InjectableValueInterface $value)
    {
        return isset($this->keys[$value]) ? $this->keys[$value] : null;
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
