<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Value\InjectableValueInterface;

class LazyValue implements InjectableValueInterface, \Serializable
{
    private $factory;
    private $result;
    private $evaluated = false;

    /**
     * @var callable $factory
     */
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
            $this->result = call_user_func($this->factory);
        }
        return $this->result;
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
        return new self(function() use ($value) {
            return unserialize($value);
        });
    }
}
