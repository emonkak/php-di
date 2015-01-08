<?php

namespace Emonkak\Di\Injection;

use Emonkak\Di\Value\InjectableValueInterface;

class MethodInjection
{
    private $method;
    private $params;

    /**
     * @param \ReflectionMethod          $method
     * @param InjectableValueInterface[] $params
     */
    public function __construct(\ReflectionMethod $method, array $params)
    {
        $this->method = $method;
        $this->params = $params;
    }

    /**
     * @return \ReflectionMethod
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return InjectableValueInterface[]
     */
    public function getParams()
    {
        return $this->parms;
    }
}
