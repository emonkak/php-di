<?php

namespace Emonkak\Di\Value;

class SingletonValue extends ObjectValue
{
    private $instance;

    /**
     * {@inheritDoc}
     */
    public function materialize()
    {
        if ($this->instance === null) {
            $this->instance = parent::materialize();
        }
        return $this->instance;
    }

    /**
     * @see \Serializable
     * @return string
     */
    public function __sleep()
    {
        return ['class', 'constructorInjection', 'methodInjections', 'propertyInjections'];
    }
}
