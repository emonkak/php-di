<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Injection\MethodInjection;
use Emonkak\Di\Injection\PropertyInjection;
use Emonkak\Di\Utils\ReflectionUtils;

class ObjectValue implements ObjectValueInterface
{
    private $className;

    private $constructorParameters;

    private $methodInjections;

    private $propertyInjections;

    /**
     * @param string                     $className
     * @param InjectableValueInterface[] $constructorParameters
     * @param array                      $methodInjections (method => InjectableValueVisitorInterface[])
     * @param array                      $propertyInjections (property => InjectableValueVisitorInterface[])
     */
    public function __construct(
        $className,
        array $constructorParameters,
        array $methodInjections,
        array $propertyInjections
    ) {
        $this->className = $className;
        $this->constructorParameters = $constructorParameters;
        $this->methodInjections = $methodInjections;
        $this->propertyInjections = $propertyInjections;
    }

    /**
     * {@inheritDoc}
     */
    public function accept(InjectableValueVisitorInterface $visitor)
    {
        return $visitor->visitObjectValue($this);
    }

    /**
     * {@inheritDoc}
     */
    public function inject()
    {
        $instance = $this->createInstance();

        foreach ($this->methodInjections as $method => $parameters) {
            $this->injectForMethod($instance, $method, $parameters);
        }

        foreach ($this->propertyInjections as $propery => $value) {
            $this->injectForProperty($instance, $propery, $value);
        }

        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * {@inheritDoc}
     */
    public function getConstructorParameters()
    {
        return $this->constructorParameters;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodInjections()
    {
        return $this->methodInjections;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyInjections()
    {
        return $this->propertyInjections;
    }

    /**
     * @return mixed
     */
    private function createInstance()
    {
        $args = [];
        foreach ($this->constructorParameters as $parameter) {
            $args[] = $parameter->inject();
        }
        return ReflectionUtils::newInstance($this->className, $args);
    }

    /**
     * @param mixed                      $instance
     * @param string                     $method
     * @param InjectableValueInterface[] $parameters
     */
    private function injectForMethod($instance, $method, array $parameters)
    {
        $args = [];
        foreach ($parameters as $parameter) {
            $args[] = $parameter->inject();
        }
        ReflectionUtils::callMethod($instance, $method, $args);
    }

    /**
     * @param mixed                    $instance
     * @param string                   $property
     * @param InjectableValueInterface $value
     */
    private function injectForProperty($instance, $property, $value)
    {
        $instance->$property = $value->inject();
    }
}
