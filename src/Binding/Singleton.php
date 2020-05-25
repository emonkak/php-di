<?php

declare(strict_types=1);

namespace Emonkak\Di\Binding;

use Emonkak\Di\Instantiator\InstantiatorInterface;

/**
 * @template T
 * @implements BindingInterface<T>
 */
class Singleton implements BindingInterface
{
    /**
     * @var BindingInterface<T>
     */
    private BindingInterface $binding;

    private bool $hasCache = false;

    /**
     * @var ?T
     */
    private $cache;

    /**
     * @param BindingInterface<T> $binding
     */
    public function __construct(BindingInterface $binding)
    {
        $this->binding = $binding;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(array $dependencies, array $bindings, InstantiatorInterface $instantiator)
    {
        if (!$this->hasCache) {
            $this->hasCache = true;
            $this->cache = $this->binding->resolve($dependencies, $bindings, $instantiator);
        }
        return $this->cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunction(): ?\ReflectionFunctionAbstract
    {
        return $this->binding->getFunction();
    }
}
