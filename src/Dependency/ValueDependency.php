<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Interop\Container\ContainerInterface;

class ValueDependency implements DependencyInterface
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        yield $this->getKey() => $this;
    }

    /**
     * {@inheritDoc}
     */
    public function accept(DependencyVisitorInterface $visitor)
    {
        return $visitor->visitValueDependency($this);
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return sha1(serialize($this->value));
    }

    /**
     * {@inheritDoc}
     */
    public function instantiateBy(ContainerInterface $container, \ArrayAccess $pool)
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
