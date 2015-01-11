<?php

namespace Emonkak\Di\Value;

interface ObjectValueInterface extends InjectableValueInterface
{
    /**
     * @return string
     */
    public function getClassName();

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
