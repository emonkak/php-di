<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\ContainerInterface;

class FlyweightFactoryDependency extends FactoryDependency
{
    /**
     * @param FactoryDependency $dependency
     * @return SharedDependency
     */
    public static function from(FactoryDependency $dependency)
    {
        return new self(
            $dependency->key,
            $dependency->factory,
            $dependency->parameters
        );
    }

    /**
     * {@inheritDoc}
     */
    public function materializeBy(ContainerInterface $container, \ArrayAccess $pool)
    {
        if (isset($pool[$this->key])) {
            return $pool[$this->key];
        }

        $instance = parent::materializeBy($container, $pool);
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
