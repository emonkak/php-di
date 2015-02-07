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
    public function materialize(ContainerInterface $container)
    {
        $args = [];
        foreach ($this->parameters as $parameter) {
            $args[] = $parameter->materialize($container);
        }
        return ReflectionUtils::callFunction($this->factory, $args);
    }

    /**
     * {@inheritDoc}
     */
    public function isSingleton()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function traverse(callable $callback)
    {
        $callback($this, $this->key);

        foreach ($this->parameters as $parameter) {
             $parameter->traverse($callback);
        }
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
