<?php

declare(strict_types=1);

namespace Emonkak\Di\Binding;

use Emonkak\Di\Instantiator\InstantiatorInterface;

/**
 * @template T
 * @implements BindingInterface<T>
 */
class Factory implements BindingInterface
{
    /**
     * @var callable(mixed...):T
     */
    private $factoryFunction;

    /**
     * @param callable(mixed...):T $factoryFunction
     */
    public function __construct(callable $factoryFunction)
    {
        $this->factoryFunction = $factoryFunction;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(array $dependencies, array $bindings, InstantiatorInterface $instantiator)
    {
        $arguments = [];
        foreach ($dependencies as $dependency) {
            $arguments[] = $instantiator->instantiate($dependency, $bindings);
        }
        return ($this->factoryFunction)(...$arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunction(): ?\ReflectionFunctionAbstract
    {
        $factoryFunction = $this->factoryFunction;

        if (is_array($factoryFunction)) {
            return new \ReflectionMethod($factoryFunction[0], $factoryFunction[1]);
        }

        if (is_object($factoryFunction) && !($factoryFunction instanceof \Closure)) {
            return new \ReflectionMethod($factoryFunction, '__invoke');
        }

        /** @var \Closure|callable-string $factoryFunction */
        return new \ReflectionFunction($factoryFunction);
    }
}
