<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\ContainerInterface;

class SharedDependency extends FactoryDependency
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
    public function inject(ContainerInterface $container)
    {
        if ($container->hasInstance($this->key)) {
            return $container->getInstance($this->key);
        }

        $instance = parent::inject($container);
        $container->setInstance($this->key, $instance);

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
