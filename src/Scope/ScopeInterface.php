<?php

namespace Emonkak\Di\Scope;

use Emonkak\Di\Value\InjectableValueInterface;

interface ScopeInterface
{
    /**
     * @param InjectableValueInterface $value
     * @return InjectableValueInterface
     */
    public function get(InjectableValueInterface $value);
}
