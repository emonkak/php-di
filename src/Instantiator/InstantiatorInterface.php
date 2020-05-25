<?php

declare(strict_types=1);

namespace Emonkak\Di\Instantiator;

use Emonkak\Di\Binding\BindingInterface;

/**
 * @template TDependency
 */
interface InstantiatorInterface
{
    /**
     * @param TDependency $target
     * @param array<string,BindingInterface> $bindings
     * @return mixed
     */
    public function instantiate($target, array $bindings);
}
