<?php

namespace Emonkak\Di\Dependency;

use Interop\Container\ContainerInterface;

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
    public function getIterator()
    {
        yield $this->key => $this;

        foreach ($this->parameters as $parameter) {
            foreach ($parameter->getIterator() as $key => $value) {
                yield $key => $value;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function accept(DependencyVisitorInterface $visitor)
    {
        return $visitor->visitFactoryDependency($this);
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return array_values($this->parameters);
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
    public function instantiateBy(ContainerInterface $container, \ArrayAccess $pool)
    {
        $args = [];
        foreach ($this->parameters as $parameter) {
            $args[] = $parameter->instantiateBy($container, $pool);
        }
        $factory = $this->factory;
        return $factory(...$args);
    }

    /**
     * @return boolean
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

    /**
     * @return SingletonFactoryDependency
     */
    public function asSingleton()
    {
        return new SingletonFactoryDependency(
            $this->key,
            $this->factory,
            $this->parameters
        );
    }
}
