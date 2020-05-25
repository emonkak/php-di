<?php

declare(strict_types=1);

namespace Emonkak\Di\Binding;

use Emonkak\Di\Instantiator\InstantiatorInterface;

/**
 * @template T
 */
interface BindingInterface
{
    /**
     * @param array[] $dependencies
     * @param BindingInterface[] $bindings
     * @return T
     */
    public function resolve(array $dependencies, array $bindings, InstantiatorInterface $instantiator);

    public function getFunction(): ?\ReflectionFunctionAbstract;
}
