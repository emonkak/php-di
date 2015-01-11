<?php

namespace Emonkak\Di\Value;

class LazyValue implements InjectableValueInterface
{
    private $factory;

    private $parameterInjections;

    /**
     * @param callable             $factory
     * @param ParameterInjection[] $parameterInjections
     */
    public function __construct(callable $factory, array $parameterInjections)
    {
        $this->factory = $factory;
        $this->parameterInjections = $parameterInjections;
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
        $params = [];
        foreach ($this->parameterInjections as $param) {
            $params[] = $param->getValue()->inject();
        }
        return call_user_func_array($this->factory, $params);
    }
}
