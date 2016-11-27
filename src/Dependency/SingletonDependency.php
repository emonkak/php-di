<?php

namespace Emonkak\Di\Dependency;

use Interop\Container\ContainerInterface;

class SingletonDependency extends ObjectDependency
{
    /**
     * {@inheritDoc}
     */
    public function instantiateBy(ContainerInterface $container, array &$pool)
    {
        if (isset($pool[$this->key])) {
            return $pool[$this->key];
        }

        $instance = parent::instantiateBy($container, $pool);
        $pool[$this->key] = $instance;

        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function isSingleton()
    {
        return true;
    }
}
