<?php

namespace Emonkak\Di\Injection;

use Emonkak\Di\Value\InjectableValueInterface;

class MethodInjection
{
    private $methodName;
    private $parameters;

    /**
     * @param string                     $methodName
     * @param InjectableValueInterface[] $parameters
     */
    public function __construct($methodName, array $parameters)
    {
        $this->methodName = $methodName;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @return InjectableValueInterface[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
