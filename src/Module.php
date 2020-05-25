<?php

declare(strict_types=1);

namespace Emonkak\Di;

use Emonkak\Di\Binding\BindingInterface;
use Emonkak\Di\Binding\Factory;
use Emonkak\Di\Binding\Implementation;
use Emonkak\Di\Binding\Singleton;
use Emonkak\Di\Binding\Value;

class Module
{
    /**
     * @var array<string,BindingInterface>
     */
    protected array $bindings;

    public function __construct(array $bindings = [])
    {
        $this->bindings = $bindings;
    }

    /**
     * @return array<string,BindingInterface>
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }

    /**
     * @template T
     * @param BindingInterface<T> $binding
     * @return BindingInterface<T>
     */
    public function addBinding(string $key, BindingInterface $binding): BindingInterface
    {
        return $this->bindings[$key] = $binding;
    }

    /**
     * @return $this
     */
    public function merge(self $other): self
    {
        $this->bindings = $other->bindings + $this->bindings;
        return $this;
    }

    /**
     * @template T
     * @param class-string<T> $key
     * @return BindingInterface<T>
     */
    public function bind(string $key): BindingInterface
    {
        return $this->bindings[$key] = new Implementation($key);
    }

    /**
     * @template T
     * @param class-string<T> $key
     * @return BindingInterface<T>
     */
    public function bindSingleton(string $key): BindingInterface
    {
        return $this->bindings[$key] = new Singleton(new Implementation($key));
    }

    /**
     * @template T
     * @param class-string<T> $className
     * @return BindingInterface<T>
     */
    public function implement(string $key, string $className): BindingInterface
    {
        return $this->bindings[$key] = new Implementation($className);
    }

    /**
     * @template T
     * @param class-string<T> $className
     * @return BindingInterface<T>
     */
    public function implementSingleton(string $key, string $className): BindingInterface
    {
        return $this->bindings[$key] = new Singleton(new Implementation($className));
    }

    /**
     * @template T
     * @param callable(mixed...):T $factoryFunction
     * @return BindingInterface<T>
     */
    public function provide(string $key, callable $factoryFunction): BindingInterface
    {
        return $this->bindings[$key] = new Factory($factoryFunction);
    }

    /**
     * @template T
     * @param callable(mixed...):T $factoryFunction
     * @return BindingInterface<T>
     */
    public function provideSingleton(string $key, callable $factoryFunction): BindingInterface
    {
        return $this->bindings[$key] = new Singleton(new Factory($factoryFunction));
    }

    /**
     * @template T
     * @param T $value
     * @return BindingInterface<T>
     */
    public function set(string $key, $value): BindingInterface
    {
        return $this->bindings[$key] = new Value($value);
    }
}
