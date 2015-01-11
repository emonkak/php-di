<?php

namespace Emonkak\Di\Value;

class SingletonValue implements ObjectValueInterface
{
    private $value;

    private $instance;

    /**
     * @param ObjectValueInterface $value
     */
    public function __construct(ObjectValueInterface $value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function accept(InjectableValueVisitorInterface $visitor)
    {
        return $visitor->visitObjectValue($this);
    }

    /**
     * {@inheritDoc}
     */
    public function inject()
    {
        if ($this->instance === null) {
            $this->instance = $this->value->inject();
        }
        return $this->instance;
    }

    /**
     * {@inheritDoc}
     */
    public function getClassName()
    {
        return $this->value->getClassName();
    }

    /**
     * {@inheritDoc}
     */
    public function getConstructorInjection()
    {
        return $this->value->getConstructorInjection();
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodInjections()
    {
        return $this->value->getMethodInjections();
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyInjections()
    {
        return $this->value->getPropertyInjections();
    }

    /**
     * @see \Serializable
     * @return string
     */
    public function __sleep()
    {
        return ['value'];
    }
}
