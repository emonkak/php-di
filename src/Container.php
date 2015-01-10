<?php

namespace Emonkak\Di;

use Emonkak\Di\Definition\AliasDefinition;
use Emonkak\Di\Definition\BindingDefinition;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\Scope\PrototypeScope;
use Emonkak\Di\Scope\ScopeInterface;
use Emonkak\Di\ValueResolver\ChainedValueResolver;
use Emonkak\Di\ValueResolver\ContainerValueResolver;
use Emonkak\Di\ValueResolver\DefaultValueResolver;
use Emonkak\Di\ValueResolver\ValueResolverInterface;
use Emonkak\Di\Value\ImmediateValue;
use Emonkak\Di\Value\InjectableValueInterface;
use Emonkak\Di\Value\LazyValue;
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
     * @return Container
     */
    public function alias($key, $target, ScopeInterface $scope = null)
    {
        $scope = $scope ?: PrototypeScope::getInstance();
        $this->definitions[$key] = new AliasDefinition($target, $scope);
        return $this;
    }

    /**
     * @param string $abstract
     * @param string $concrete
     * @param ScopeInterface|null $scope
     * @return Container
     */
    public function bind($abstract, $concrete, ScopeInterface $scope = null)
    {
        $abstractClass = new \ReflectionClass($abstract);
        $concreteClass = new \ReflectionClass($concrete);

        if (!$concreteClass->isSubclassOf($abstractClass)) {
            throw new \InvalidArgumentException("`$abstract` is not sub-class of `$concrete`");
        }

        $key = $abstractClass->getName();
        $definition = $this->getDefinitionByClass($concreteClass, $scope);

        $this->definitions[$key] = $definition;

        return $this;
    }

    /**
     * @param string $target
     * @param ScopeInterface|null $scope
     * @return Container
     */
    public function register($target, ScopeInterface $scope = null)
    {
        $targetClass = new \ReflectionClass($target);

        $key = $targetClass->getName();
        $definition = $this->getDefinitionByClass($targetClass, $scope);

        $this->definitions[$key] = $definition;

        return $this;
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
     * @param string   $key
     * @param callable $factory
     * @return Container
     */
    public function factory($key, callable $factory)
    {
        $this->setValue($key, new LazyValue($factory));
        return $this;
    }

    /**
     * @param string $key
     * @param string $tag
     * @return Container
     */
    public function undefined($key, $tag)
    {
        $this->setValue($key, new UndefinedValue($tag));
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
                throw new \InvalidArgumentException('Key not registered: ' . $key);
            }
            $class = new \ReflectionClass($key);
            $definition = $this->getDefinitionByClass($class);
        }

        $value = $definition->resolve($this);
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

    /**
     * @param \ReflectionClass    $class
     * @param ScopeInterface|null $scope
     * @return InjectableValueInterface
     */
    private function getDefinitionByClass(\ReflectionClass $class, ScopeInterface $scope = null)
    {
        if (!$this->injectionPolicy->isInjectableClass($class)) {
            throw new \InvalidArgumentException(
                sprintf('Class "%s" does not be injectable.', $class->getName())
            );
        }

        $scope = $scope ?: $this->injectionPolicy->getScope($class);

        return new BindingDefinition($class, $scope);
    }
}
