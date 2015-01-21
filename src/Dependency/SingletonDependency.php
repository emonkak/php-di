<?php

namespace Emonkak\Di\Dependency;

class SingletonDependency extends ObjectDependency 
{
    /**
     * @var mixed
     */
    private $instance;

    /**
     * @param ObjectDependency $dependency
     * @return SingletonDependency
     */
    public static function from(ObjectDependency $dependency)
    {
        return new self(
            $dependency->className,
            $dependency->constructorParameters,
            $dependency->methodInjections,
            $dependency->propertyInjections
        );
    }

    /**
     * @return string[]
     */
    public function __sleep()
    {
        return ['className', 'constructorParameters', 'methodInjections', 'propertyInjections'];
    }

    /**
     * {@inheritDoc}
     */
    public function inject(\ArrayAccess $valueBag)
    {
        if ($this->instance === null) {
            $this->instance = parent::inject($valueBag);
        }
        return $this->instance;
    }
}
