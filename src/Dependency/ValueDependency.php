<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Definition\DefinitionInterface;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\ResolverInterface;

class ValueDependency implements DefinitionInterface, DependencyInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
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
    public function resolveBy(ResolverInterface $resolver, InjectionPolicyInterface $injectionPolicy)
    {
        return $this;
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
        return $this->key;
    }

    /**
     * {@inheritDoc}
     */
    public function instantiateBy(ContainerInterface $container)
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
