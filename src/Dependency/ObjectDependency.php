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
    public function acceptVisitor(DependencyVistorInterface $visitor)
    {
        return $visitor->visitObjectDependency($this);
    }

    /**
     * {@inheritDoc}
     */
    public function acceptTraverser(DependencyTraverserInterface $traverser)
    {
        yield $this->getKey() => $traverser->map($this);

        foreach ($this->constructorParameters as $parameter) {
            foreach ($parameter->acceptTraverser($traverser) as $key => $result) {
                yield $key => $result;
            }
        }

        foreach ($this->methodInjections as $method => $parameters) {
            foreach ($parameters as $parameter) {
                foreach ($parameter->acceptTraverser($traverser) as $key => $result) {
                    yield $key => $result;
                }
            }
        }

        foreach ($this->propertyInjections as $propery => $value) {
            foreach ($value->acceptTraverser($traverser) as $key => $result) {
                yield $key => $result;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function inject(ContainerInterface $container)
    {
        $instance = $this->instantiate($container);

        foreach ($this->methodInjections as $method => $parameters) {
            $this->injectForMethod($instance, $method, $parameters, $container);
        }

        foreach ($this->propertyInjections as $propery => $value) {
            $this->injectForProperty($instance, $propery, $value, $container);
        }

        return $instance;
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

    /**
     * @param ContainerInterface $container
     * @return mixed
     */
    private function instantiate(ContainerInterface $container)
    {
        $args = [];
        foreach ($this->constructorParameters as $parameter) {
            $args[] = $parameter->inject($container);
        }
        return ReflectionUtils::newInstance($this->className, $args);
    }

    /**
     * @param mixed                 $instance
     * @param string                $method
     * @param DependencyInterface[] $parameters
     * @param ContainerInterface    $container
     */
    private function injectForMethod($instance, $method, array $parameters, ContainerInterface $container)
    {
        $args = [];
        foreach ($parameters as $parameter) {
            $args[] = $parameter->inject($container);
        }
        ReflectionUtils::callMethod($instance, $method, $args);
    }

    /**
     * @param mixed               $instance
     * @param string              $property
     * @param DependencyInterface $value
     * @param ContainerInterface  $container
     */
    private function injectForProperty($instance, $property, DependencyInterface $value, ContainerInterface $container)
    {
        $instance->$property = $value->inject($container);
    }
}
