<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Value\InjectableValueInterface;

class LazyValue implements InjectableValueInterface, \Serializable
{
    private $factory;
    private $value;
    private $evaluated = false;

    public function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritDoc}
     */
    public function accept(InjectableValueVisitorInterface $visitor)
    {
        return $visitor->visitValue($this);
    }

    /**
     * {@inheritDoc}
     */
    public function materialize()
    {
        if (!$this->evaluated) {
            $this->evaluated = true;
            $this->value = call_user_func($this->factory);
        }
        return $this->value;
    }

    /**
     * @see \Serializable
     * @return string
     */
    public function serialize()
    {
        return serialize($this->materialize());
    }

    /**
     * @see \Serializable
     * @return string
     */
    public function unserialize($value)
    {
        return new ImmediateValue(unserialize($value));
    }
}
