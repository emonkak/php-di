<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\Scope\ScopeInterface;
use Emonkak\Di\Utils\ReflectionUtils;

class ObjectDependency implements DependencyInterface
{
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
     * @param string                $className
     * @param DependencyInterface[] $constructorParameters
     * @param array                 $methodInjections      (string => DependencyInterface[])
     * @param array                 $propertyInjections    (string => DependencyInterface)
     */
    public function __construct($className, array $constructorParameters, array $methodInjections, array $propertyInjections)
    {
        $this->className = $className;
        $this->constructorParameters = $constructorParameters;
        $this->methodInjections = $methodInjections;
        $this->propertyInjections = $propertyInjections;
    }

    /**
     * {@inheritDoc}
     */
    public function accept(DependencyVistorInterface $visitor)
    {
        return $visitor->visitObjectDependency($this);
    }

    /**
     * {@inheritDoc}
     */
    public function inject(\ArrayAccess $valueBag)
    {
        $instance = $this->instantiate($valueBag);

        foreach ($this->methodInjections as $method => $parameters) {
            $this->injectForMethod($instance, $method, $parameters, $valueBag);
        }

        foreach ($this->propertyInjections as $propery => $value) {
            $this->injectForProperty($instance, $propery, $value, $valueBag);
        }

        return $instance;
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
     * @return mixed
     */
    private function instantiate(\ArrayAccess $valueBag)
    {
        $args = [];
        foreach ($this->constructorParameters as $parameter) {
            $args[] = $parameter->inject($valueBag);
        }
        return ReflectionUtils::newInstance($this->className, $args);
    }

    /**
     * @param mixed                 $instance
     * @param string                $method
     * @param DependencyInterface[] $parameters
     * @param \ArrayAccess          $valueBag
     */
    private function injectForMethod($instance, $method, array $parameters, \ArrayAccess $valueBag)
    {
        $args = [];
        foreach ($parameters as $parameter) {
            $args[] = $parameter->inject($valueBag);
        }
        ReflectionUtils::callMethod($instance, $method, $args);
    }

    /**
     * @param mixed               $instance
     * @param string              $property
     * @param DependencyInterface $value
     * @param \ArrayAccess        $valueBag
     */
    private function injectForProperty($instance, $property, DependencyInterface $value, \ArrayAccess $valueBag)
    {
        $instance->$property = $value->inject($valueBag);
    }
}
