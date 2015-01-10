<?php

namespace Emonkak\Di;

use Emonkak\Di\Definition\AliasDefinition;
use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\Definition\FactoryDefinition;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\Scope\PrototypeScope;
use Emonkak\Di\Scope\ScopeInterface;
use Emonkak\Di\ValueResolver\ChainedValueResolver;
use Emonkak\Di\ValueResolver\ContainerValueResolver;
use Emonkak\Di\ValueResolver\DefaultValueResolver;
use Emonkak\Di\ValueResolver\ValueResolverInterface;
use Emonkak\Di\Value\ImmediateValue;
use Emonkak\Di\Value\InjectableValueInterface;
use Emonkak\Di\Value\UndefinedValue;

class Container
{
    private $valueResolver;

    private $injectionFinder;

    private $injectionPolicy;

    private $definitions = [];

    private $values = [];

    private $keys;

    /**
     * @param InjectionPolicyInterface $injectionPolicy
     */
    public function __construct(InjectionPolicyInterface $injectionPolicy)
    {
        $this->valueResolver = new ChainedValueResolver(
            new ContainerValueResolver($this, $injectionPolicy),
            new DefaultValueResolver()
        );
        $this->injectionFinder = new InjectionFinder($this->valueResolver, $injectionPolicy);
        $this->injectionPolicy = $injectionPolicy;
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
     * @param ValueResolverInterface $valueResolver
     * @return Container
     */
    public function appendValueResolver(ValueResolverInterface $valueResolver)
    {
        $this->valueResolver = new ChainedValueResolver(
            $this->valueResolver,
            $valueResolver
        );
        return $this;
    }

    /**
     * @param ValueResolverInterface $valueResolver
     * @return Container
     */
    public function prependValueResolver(ValueResolverInterface $valueResolver)
    {
        $this->valueResolver = new ChainedValueResolver(
            $valueResolver,
            $this->valueResolver
        );
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
        $targetClass = new \ReflectionClass($target);
        $key = $targetClass->getName();
        return $this->definitions[$key] = new BindingDefinition($targetClass);
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
        $this->values[$key] = $value;
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
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }

        if (isset($this->definitions[$key])) {
            $definition = $this->definitions[$key];
        } else {
            if (!class_exists($key)) {
                throw new \InvalidArgumentException(
                    sprintf('The key "%s" does not registered in this container.', $key)
                );
            }
            $class = new \ReflectionClass($key);
            $definition = new BindingDefinition($class);
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
        return isset($this->values[$key]) || isset($this->definitions[$key]) || class_exists($key);
    }
}
