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
