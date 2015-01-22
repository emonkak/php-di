<?php

namespace Emonkak\Di\Dependency;

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
