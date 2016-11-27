<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\Scope\ScopeInterface;
use Interop\Container\ContainerInterface;

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
    protected $constructorDependencies;

    /**
     * @var array (string => DependencyInterface[])
     */
    protected $methodDependencies;

    /**
     * @var array (string => DependencyInterface)
     */
    protected $propertyDependencies;

    /**
     * @param string                $key
     * @param string                $className
     * @param DependencyInterface[] $constructorDependencies
     * @param array                 $methodDependencies    (string => DependencyInterface[])
     * @param array                 $propertyDependencies  (string => DependencyInterface)
     */
    public function __construct($key, $className, array $constructorDependencies, array $methodDependencies, array $propertyDependencies)
    {
        $this->key = $key;
        $this->className = $className;
        $this->constructorDependencies = $constructorDependencies;
        $this->methodDependencies = $methodDependencies;
        $this->propertyDependencies = $propertyDependencies;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        yield $this->key => $this;

        foreach ($this->constructorDependencies as $key => $dependency) {
            foreach ($dependency as $key => $value) {
                yield $key => $value;
            }
        }

        foreach ($this->methodDependencies as $dependencies) {
            foreach ($dependencies as $dependency) {
                foreach ($dependency as $key => $value) {
                    yield $key => $value;
                }
            }
        }

        foreach ($this->propertyDependencies as $dependency) {
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
        return $visitor->visitObjectDependency($this);
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        $dependencies = $this->constructorDependencies;

        foreach ($this->methodDependencies as $method => $parameters) {
            $dependencies = array_merge($dependencies, $parameters);
        }

        return array_merge($dependencies, array_values($this->propertyDependencies));
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
    public function materializeBy(ContainerInterface $container, \ArrayAccess $pool)
    {
        $args = [];
        foreach ($this->constructorDependencies as $parameter) {
            $args[] = $parameter->materializeBy($container, $pool);
        }
        $instance = new $this->className(...$args);

        foreach ($this->methodDependencies as $method => $parameters) {
            $args = [];
            foreach ($parameters as $parameter) {
                $args[] = $parameter->materializeBy($container, $pool);
            }
            $instance->$method(...$args);
        }

        foreach ($this->propertyDependencies as $property => $value) {
            $instance->$property = $value->materializeBy($container, $pool);
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
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return DependencyInterface[]
     */
    public function getConstructorDependencies()
    {
        return $this->constructorDependencies;
    }

    /**
     * @return array array(string => DependencyInterface[])
     */
    public function getMethodDependencies()
    {
        return $this->methodDependencies;
    }

    /**
     * @return array array(string => DependencyInterface)
     */
    public function getPropertyDependencies()
    {
        return $this->propertyDependencies;
    }

    /**
     * @return SingletonDependency
     */
    public function asSingleton()
    {
        return new SingletonDependency(
            $this->key,
            $this->className,
            $this->constructorDependencies,
            $this->methodDependencies,
            $this->propertyDependencies
        );
    }
}
