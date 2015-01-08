<?php

namespace Emonkak\Di\Injection;

class MethodInjection
{
    private $method;
    private $params;

    /**
     * @param \ReflectionMethod    $method
     * @param ParameterInjection[] $params
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
     * @return ParameterInjection[]
     */
    public function getParameters()
    {
        return $this->params;
    }
}
