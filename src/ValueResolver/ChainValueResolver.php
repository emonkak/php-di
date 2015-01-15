<?php

namespace Emonkak\Di\ValueResolver;

class ChainValueResolver implements ValueResolverInterface
{
    /**
     * @var ValueResolverInterface[]
     */
    private $resolvers;

    /**
     * @param ValueResolverInterface[] $resolvers
     */
    public function __construct(array $resolvers = [])
    {
        $this->resolvers = $resolvers;
    }

    /**
     * @param ValueResolverInterface $resolver
     */
    public function append(ValueResolverInterface $resolver)
    {
        $this->resolvers[] = $resolver;
    }

    /**
     * @param ValueResolverInterface $resolver
     */
    public function prepend(ValueResolverInterface $resolver)
    {
        array_unshift($this->resolvers, $resolver);
    }

    /**
     * {@inheritDoc}
     */
    public function getParameterValue(\ReflectionParameter $parameter)
    {
        foreach ($this->resolvers as $resolver) {
            $value = $resolver->getParameterValue($parameter);
            if ($value) {
                return $value;
            }
        }
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyValue(\ReflectionProperty $property)
    {
        foreach ($this->resolvers as $resolver) {
            $value = $resolver->getPropertyValue($property);
            if ($value) {
                return $value;
            }
        }
        return null;
    }
}
