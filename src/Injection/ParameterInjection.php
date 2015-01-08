<?php

namespace Emonkak\Di\Injection;

use Emonkak\Di\Value\InjectableValueInterface;

class ParameterInjection
{
    private $param;
    private $value;

    /**
     * @param \ReflectionParameter     $param
     * @param InjectableValueInterface $value
     */
    public function __construct(\ReflectionParameter $param, InjectableValueInterface $value)
    {
        $this->param = $param;
        $this->value = $value;
    }

    /**
     * @return \ReflectionParameter
     */
    public function getParameter()
    {
        return $this->param;
    }

    /**
     * @return InjectableValueInterface
     */
    public function getValue()
    {
        return $this->value;
    }
}
