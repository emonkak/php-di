<?php

namespace Emonkak\Di\Value;

class LazyValue implements InjectableValueInterface
{
    private $factory;

    private $parameters;

    /**
     * @param callable                          $factory
     * @param InjectableValueVisitorInterface[] $parameters
     */
    public function __construct(callable $factory, array $parameters)
    {
        $this->factory = $factory;
        $this->parameters = $parameters;
    }

    /**
     * {@inheritDoc}
     */
    public function accept(InjectableValueVisitorInterface $visitor)
    {
        return $visitor->visitValue($this);
    }

    /**
     * {@inheritDoc}
     */
    public function inject()
    {
        $args = [];
        foreach ($this->parameters as $parameter) {
            $args[] = $parameter->inject();
        }
        return call_user_func_array($this->factory, $args);
    }
}
