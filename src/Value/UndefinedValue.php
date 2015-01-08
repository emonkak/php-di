<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Injector;

class UndefinedValue implements InjectableValueInterface
{
    private function __construct() {}

    /**
     * @return UndefinedValue
     */
    public static function getInstance()
    {
        static $instance;

        if (!isset($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @param Injector $injector
     * @return mixed
     */
    public function materialize(Injector $injector)
    {
        return new \RuntimeException('Undefined can not be materialized.');
    }
}
