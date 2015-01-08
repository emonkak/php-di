<?php

namespace Emonkak\Di\Injection;

use Emonkak\Di\Value\InjectableValueInterface;

class PropertyInjection
{
    private $property;
    private $value;

    /**
     * @param \ReflectionProperty      $property
     * @param InjectableValueInterface $value
     */
    public function __construct(\ReflectionProperty $property, InjectableValueInterface $value)
    {
        $this->property = $property;
        $this->value = $value;
    }

    /**
     * @return \ReflectionProperty
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @return InjectableValueInterface[]
     */
    public function getValue()
    {
        return $this->value;
    }
}
