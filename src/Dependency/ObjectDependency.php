<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Scope\ScopeInterface;
use Emonkak\Di\Utils\ReflectionUtils;

class ObjectDependency implements DependencyInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var DependencyInterface[]
     */
    protected $constructorParameters;

    /**
     * @var array (string => DependencyInterface[])
     */
    protected $methodInjections;

    /**
     * @var array (string => DependencyInterface)
     */
    protected $propertyInjections;

    /**
     * @param string                $key
     * @param string                $className
     * @param DependencyInterface[] $constructorParameters
     * @param array                 $methodInjections      (string => DependencyInterface[])
     * @param array                 $propertyInjections    (string => DependencyInterface)
     */
    public function __construct($key, $className, array $constructorParameters, array $methodInjections, array $propertyInjections)
    {
        $this->key = $key;
        $this->className = $className;
        $this->constructorParameters = $constructorParameters;
        $this->methodInjections = $methodInjections;
        $this->propertyInjections = $propertyInjections;
    }

    /**
     * {@inheritDoc}
     */
    public function accept(DependencyVisitorInterface $visitor)
    {
        return $visitor->visitObjectDependency($this);
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        $dependencies = $this->constructorParameters;

        foreach ($this->methodInjections as $method => $parameters) {
            $dependencies = array_merge($dependencies, array_values($parameters));
        }

        return array_merge($dependencies, array_values($this->propertyInjections));
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
        foreach ($this->constructorParameters as $parameter) {
            $args[] = $parameter->materialize($container);
        }
        $instance = ReflectionUtils::newInstance($this->className, $args);

        foreach ($this->methodInjections as $method => $parameters) {
            $args = [];
            foreach ($parameters as $parameter) {
                $args[] = $parameter->materialize($container);
            }
            ReflectionUtils::callMethod($instance, $method, $args);
        }

        foreach ($this->propertyInjections as $property => $value) {
            $instance->$property = $value->materialize($container);
        }

        return $instance;
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

        foreach ($this->constructorParameters as $parameter) {
            $parameter->traverse($callback);
        }

        foreach ($this->methodInjections as $method => $parameters) {
            foreach ($parameters as $parameter) {
                $parameter->traverse($callback);
            }
        }

        foreach ($this->propertyInjections as $propery => $value) {
            $value->traverse($callback);
        }
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return DependencyInterface[]
     */
    public function getConstructorParameters()
    {
        return $this->constructorParameters;
    }

    /**
     * @return array (string => DependencyInterface[])
     */
    public function getMethodInjections()
    {
        return $this->methodInjections;
    }

    /**
     * @return array (string => DependencyInterface)
     */
    public function getPropertyInjections()
    {
        return $this->propertyInjections;
    }
}
