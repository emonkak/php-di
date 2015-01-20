<?php

namespace Emonkak\Di\Value;

interface ObjectValueInterface extends InjectableValueInterface
{
    /**
     * @return string
     */
    public function getClassName();

    /**
     * @return InjectableValueInterface[]
     */
    public function getConstructorParameters();

    /**
     * @return array (method => InjectableValueInterface[])
     */
    public function getMethodInjections();

    /**
     * @return array (property => InjectableValueInterface[])
     */
    public function getPropertyInjections();
}
