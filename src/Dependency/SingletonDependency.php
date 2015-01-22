<?php

namespace Emonkak\Di\Dependency;

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
    public function inject(\ArrayAccess $valueBag)
    {
        if (isset($valueBag[$this->key])) {
            return $valueBag[$this->key];
        }

        $value = parent::inject($valueBag);
        $valueBag[$this->key] = $value;

        return $value;
    }
}
