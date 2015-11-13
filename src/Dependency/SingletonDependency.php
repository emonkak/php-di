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
            $dependency->constructorDependencies,
            $dependency->methodDependencies,
            $dependency->propertyDependencies
        );
    }

    /**
     * {@inheritDoc}
     */
    public function materializeBy(ContainerInterface $container)
    {
        if ($container->hasValue($this->key)) {
            return $container->getValue($this->key);
        }

        $instance = parent::materializeBy($container);
        $container->setValue($this->key, $instance);

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
