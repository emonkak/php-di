<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\ContainerInterface;

class SingletonDependency extends ObjectDependency 
{
    /**
     * @param ObjectDependency $dependency
     * @return SingletonDependency
     */
    public static function from(ObjectDependency $dependency)
    {
        return new self(
            $dependency->key,
            $dependency->className,
            $dependency->constructorParameters,
            $dependency->methodInjections,
            $dependency->propertyInjections
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
