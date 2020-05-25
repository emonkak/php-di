<?php

declare(strict_types=1);

namespace Emonkak\Di\Instantiator;

/**
 * @implements InstantiatorInterface<array>
 */
class Instantiator implements InstantiatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function instantiate($target, array $bindings)
    {
        [$key, $dependencies] = $target;

        if (isset($bindings[$key])) {
            $binding = $bindings[$key];
            return $binding->resolve($dependencies, $bindings, $this);
        } elseif ($dependencies !== null) {
            $arguments = [];
            foreach ($dependencies as $dependency) {
                $arguments[] = $this->instantiate($dependency, $bindings);
            }
            return new $key(...$arguments);
        } else {
            $defaultValue = $target[2] ?? null;
            return $defaultValue;
        }
    }
}
