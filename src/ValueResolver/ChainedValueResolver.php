<?php

namespace Emonkak\Di\ValueResolver;

class ChainedValueResolver implements ValueResolverInterface
{
    private $first;
    private $second;

    /**
     * @param ValueResolverInterface $first
     * @param ValueResolverInterface $second
     */
    public function __construct(ValueResolverInterface $first, ValueResolverInterface $second)
    {
        $this->first = $first;
        $this->second = $second;
    }

    /**
     * {@inheritDoc}
     */
    public function getParameterValue(\ReflectionParameter $param)
    {
        return $this->first->getParameterValue($param)
            ?: $this->second->getParameterValue($param);
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyValue(\ReflectionProperty $property)
    {
        return $this->first->getPropertyValue($param)
            ?: $this->second->getPropertyValue($param);
    }
}
