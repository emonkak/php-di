<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Injector;

class LazyValue implements InjectableValueInterface
{
    private $func;
    private $value;
    private $evaluated = false;

    public function __construct(callable $func)
    {
        $this->func = $func;
    }

    /**
     * @param Injector $injector
     * @return mixed
     */
    public function materialize(Injector $injector)
    {
        if (!$this->evaluated) {
            $this->evaluated = true;
            $this->value = call_user_func($this->func);
        }
        return $this->value;
    }
}
