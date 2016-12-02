<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\ContainerInterface;

class FactoryDependency implements DependencyInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var callable
     */
    protected $factory;

    /**
     * @var DependencyInterface[]
     */
    protected $parameterDependencies;

    /**
     * @param string                $key
     * @param callable              $factory
     * @param DependencyInterface[] $parameterDependencies
     */
    public function __construct($key, callable $factory, array $parameterDependencies)
    {
        $this->key = $key;
        $this->factory = $factory;
        $this->parameterDependencies = $parameterDependencies;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        yield $this->key => $this;

        foreach ($this->parameterDependencies as $dependency) {
            foreach ($dependency as $key => $value) {
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
        return $this->parameterDependencies;
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
        $args = [];
        foreach ($this->parameterDependencies as $dependency) {
            $args[] = $dependency->instantiateBy($container);
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
     * @return SingletonFactoryDependency
     */
    public function asSingleton()
    {
        return new SingletonFactoryDependency(
            $this->key,
            $this->factory,
            $this->parameterDependencies
        );
    }
}
