<?php

namespace Emonkak\Di\Injection;

use Emonkak\Di\Value\InjectableValueInterface;

class PropertyInjection
{
    private $propertyName;

    private $value;

    /**
     * @param string                   $propertyName
     * @param InjectableValueInterface $value
     */
    public function __construct($propertyName, InjectableValueInterface $value)
    {
        $this->propertyName = $propertyName;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @return InjectableValueInterface
     */
    public function getValue()
    {
        return $this->value;
    }
}
