<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\Utils\ReflectionUtils;

class FactoryDependency implements DependencyInterface
{
    /**
     * @var callbale
     */
    protected $factory;

    /**
     * @var DependencyInterface[]
     */
    protected $parameters;

    /**
     * @param callable              $factory
     * @param DependencyInterface[] $parameters
     */
    public function __construct(callable $factory, array $parameters)
    {
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
            $args[] = $parameter->accept($this);
        }
        return ReflectionUtils::callFunction($this->factory, $args);
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
