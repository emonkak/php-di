<?php

declare(strict_types=1);

namespace Emonkak\Di\Inspector;

use Emonkak\Di\Binding\BindingInterface;

/**
 * @template TDependency
 */
interface InspectorInterface
{
    /**
     * @param array<string,BindingInterface> $bindings
     * @return TDependency
     */
    public function inspect(string $key, array $bindings);
}
