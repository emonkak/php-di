<?php

namespace Emonkak\Di\Value;

interface ObjectValueInterface extends InjectableValueInterface
{
    /**
     * @return \ReflectionClass
     */
    public function getClass();

    /**
     * @return MethodInjection|null
     */
    public function getConstructorInjection();

    /**
     * @return MethodInjection[]
     */
    public function getMethodInjections();

    /**
     * @return PropertyInjection[]
     */
    public function getPropertyInjections();
}
