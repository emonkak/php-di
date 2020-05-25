<?php

declare(strict_types=1);

namespace Emonkak\Di\Binding;

use Emonkak\Di\Instantiator\InstantiatorInterface;

/**
 * @template T
 * @implements BindingInterface<T>
 */
class Value implements BindingInterface
{
    /**
     * @var T
     */
    private $value;

    /**
     * @param T $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(array $dependencies, array $bindings, InstantiatorInterface $instantiator)
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunction(): ?\ReflectionFunctionAbstract
    {
        return null;
    }
}
