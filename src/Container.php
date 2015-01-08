<?php

namespace Emonkak\Di;

use Emonkak\Di\Binding\AliasBinding;
use Emonkak\Di\Binding\ObjectBinding;
use Emonkak\Di\Binding\SingletonBinding;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\ValueResolver\ChainedValueResolver;
use Emonkak\Di\ValueResolver\ContainerValueResolver;
use Emonkak\Di\ValueResolver\DefaultValueResolver;
use Emonkak\Di\ValueResolver\ValueResolverInterface;
use Emonkak\Di\Value\ImmediateValue;
use Emonkak\Di\Value\LazyValue;
use Emonkak\Di\Value\UndefinedValue;

class Container
{
    private $valueResolver;

    private $injectionFinder;

    private $injectionPolicy;

    private $values = [];

    private $bindings = [];

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
     */
    public function addValueResolver(ValueResolverInterface $valueResolver)
    {
        $this->valueResolver = new ChainedValueResolver(
            $valueResolver,
            $this->valueResolver
        );
    }

    /**
     * @param string $key
     * @param string $target
     * @return Container
     */
    public function alias($key, $target)
    {
        $this->bindings[$key] = new AliasBinding($target);
        return $this;
    }

    /**
     * @param string $abstract
     * @param string $concrete
     * @return Container
     */
    public function bind($abstract, $concrete)
    {
        $abstractClass = new \ReflectionClass($abstract);
        $concreteClass = new \ReflectionClass($concrete);

        if (!$concreteClass->isSubclassOf($abstractClass)) {
            throw new \InvalidArgumentException("`$abstract` is not sub-class of `$concrete`");
        }

        $binding = $this->getBindingByClass($concreteClass);
        $this->bindings[$abstractClass->getName()] = $binding;

        return $this;
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @return Container
     */
    public function set($key, $value)
    {
        $this->values[$key] = new ImmediateValue($value);
        return $this;
    }

    /**
     * @param string   $key
     * @param callable $factory
     * @return Container
     */
    public function factory($key, callable $factory)
    {
        $this->values[$key] = new LazyValue($factory);
        return $this;
    }

    /**
     * @param string $key
     * @param string $tag
     * @return Container
     */
    public function undefined($key, $tag)
    {
        $this->values[$key] = new UndefinedValue($tag);
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

        if (isset($this->bindings[$key])) {
            $binding = $this->bindings[$key];
        } else {
            try {
                $class = new \ReflectionClass($key);
            } catch (\ReflectionException $e) {
                throw new \InvalidArgumentException('Key not registered: ' . $key, $e);
            }
            $binding = $this->getBindingByClass($class);
        }

        $value = $binding->toInjectableValue($this);
        $this->values[$key] = $value;
        return $value;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return isset($this->values[$key]) || isset($this->bindings[$key]) || class_exists($key);
    }

    /**
     * @return InjectableValueInterface
     */
    private function getBindingByClass(\ReflectionClass $class)
    {
        if (!$this->injectionPolicy->isInjectable($class)) {
            throw new \InvalidArgumentException(
                sprintf('Class "%s" does not be injectable.', $class->getName())
            );
        }

        $binding = new ObjectBinding($class);

        if ($this->injectionPolicy->isSingleton($class)) {
            $binding = new SingletonBinding($binding);
        }

        return $binding;
    }
}
