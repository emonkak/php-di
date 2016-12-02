<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\ContainerInterface;

class SingletonFactoryDependency extends FactoryDependency
{
    /**
     * {@inheritDoc}
     */
    public function instantiateBy(ContainerInterface $container)
    {
        if ($container->isStored($this->key)) {
            $instance = $container->get($this->key);
        } else {
            $instance = parent::instantiateBy($container);
            $container->store($this->key, $instance);
        }

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
