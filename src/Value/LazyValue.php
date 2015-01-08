<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Injector;

class LazyValue implements InjectableValueInterface
{
    private $factory;
    private $value;
    private $evaluated = false;

    public function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritDoc}
     */
    public function materialize()
    {
        if (!$this->evaluated) {
            $this->evaluated = true;
            $this->value = call_user_func($this->factory);
        }
        return $this->value;
    }
}
