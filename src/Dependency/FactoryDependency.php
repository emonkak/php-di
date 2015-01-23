<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\ContainerInterface;
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
    public function acceptVisitor(DependencyVistorInterface $visitor)
    {
        return $visitor->visitFactoryDependency($this);
    }

    /**
     * {@inheritDoc}
     */
    public function acceptTraverser(DependencyTraverserInterface $traverser)
    {
        yield $this->getKey() => $traverser->map($this);

        foreach ($this->parameters as $parameter) {
            foreach ($parameter->acceptTraverser($traverser) as $result) {
                yield $result;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function inject(ContainerInterface $container)
    {
        $args = [];
        foreach ($this->parameters as $parameter) {
            $args[] = $parameter->inject($container);
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
     * {@inheritDoc}
     */
    public function isSingleton()
    {
        return false;
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
