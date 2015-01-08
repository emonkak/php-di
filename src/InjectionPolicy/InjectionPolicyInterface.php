<?php

namespace Emonkak\Di\InjectionPolicy;

interface InjectionPolicyInterface
{
    /**
     * @param \ReflectionClass $class
     * @return \ReflectionMethod[]
     */
    public function getInjectableMethods(\ReflectionClass $class);

    /**
     * @param \ReflectionClass $class
     * @return \ReflectionProperty[]
     */
    public function getInjectableProperties(\ReflectionClass $class);

    /**
     * @param \ReflectionParameter $param
     * @return string
     */
    public function getParameterKey(\ReflectionParameter $param);

    /**
     * @param \ReflectionProperty $prop
     * @return string
     */
    public function getPropertyKey(\ReflectionProperty $prop);

    /**
     * @param \ReflectionClass $class
     * @return boolean
     */
    public function isSingleton(\ReflectionClass $class);
}
