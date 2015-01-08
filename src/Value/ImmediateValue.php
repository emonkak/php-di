<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Injector;

class ImmediateValue implements InjectableValueInterface
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param Injector $injector
     * @return mixed
     */
    public function materialize(Injector $injector)
    {
        return $this->value;
    }
}
