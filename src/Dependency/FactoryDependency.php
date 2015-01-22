<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\Utils\ReflectionUtils;

class FactoryDependency implements DependencyInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var callbale
     */
    protected $factory;

    /**
     * @var DependencyInterface[]
     */
    protected $parameters;

    /**
     * @param string                $key
     * @param callable              $factory
     * @param DependencyInterface[] $parameters
     */
    public function __construct($key, callable $factory, array $parameters)
    {
        $this->key = $key;
        $this->factory = $factory;
        $this->parameters = $parameters;
    }

    /**
     * {@inheritDoc}
     */
    public function accept(DependencyVistorInterface $visitor)
    {
        return $visitor->visitFactoryDependency($this);
    }

    /**
     * {@inheritDoc}
     */
    public function inject(\ArrayAccess $valueBag)
    {
        $args = [];
        foreach ($this->parameters as $parameter) {
            $args[] = $parameter->inject($valueBag);
        }
        return ReflectionUtils::callFunction($this->factory, $args);
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return callable
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return DependencyInterface[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
