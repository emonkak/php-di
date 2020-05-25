<?php

declare(strict_types=1);

namespace Emonkak\Di\Binding;

use Emonkak\Di\Instantiator\InstantiatorInterface;

/**
 * @template T
 * @implements BindingInterface<T>
 */
class Implementation implements BindingInterface
{
    /**
     * @var class-string<T>
     */
    private string $className;

    /**
     * @param class-string<T> $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
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
        return new $this->className(...$arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunction(): ?\ReflectionFunctionAbstract
    {
        $class = new \ReflectionClass($this->className);
        return $class->getConstructor();
    }
}
