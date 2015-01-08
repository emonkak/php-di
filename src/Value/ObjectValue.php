<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Injection\MethodInjection;
use Emonkak\Di\Injection\PropertyInjection;
use Emonkak\Di\Injector;

class ObjectValue implements InjectableValueInterface
{
    private $class;
    private $constructorInjection;
    private $methodInjections;
    private $propertyInjections;

    /**
     * @param \ReflectionClass    $class
     * @param MethodInjection     $constructorInjection
     * @param MethodInjection[]   $methodInjections
     * @param PropertyInjection[] $propertyInjections
     */
    public function __construct(
        \ReflectionClass $class,
        MethodInjection $constructorInjection = null,
        array $methodInjections = [],
        array $propertyInjections = []
    ) {
        $this->class = $class;
        $this->constructorInjection = $constructorInjection;
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
    public function materialize()
    {
        $instance = $this->class->newInstanceWithoutConstructor();

        if ($this->constructorInjection) {
            $this->injectForMethod($instance, $this->constructorInjection);
        }

        foreach ($this->methodInjections as $methodInjection) {
            $this->injectForMethod($instance, $methodInjection);
        }

        foreach ($this->propertyInjections as $propertyInjection) {
            $this->injectForProperty($instance, $propertyInjection);
        }

        return $instance;
    }

    /**
     * @return \ReflectionClass
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return MethodInjection
     */
    public function getConstructorInjection()
    {
        return $this->constructorInjection;
    }

    /**
     * @return MethodInjection[]
     */
    public function getMethodInjections()
    {
        return $this->methodInjections;
    }

    /**
     * @return PropertyInjection[]
     */
    public function getPropertyInjections()
    {
        return $this->propertyInjections;
    }

    /**
     * @param mixed           $instance
     * @param MethodInjection $methodInjection
     */
    private function injectForMethod($instance, MethodInjection $methodInjection)
    {
        $params = [];
        foreach ($methodInjection->getParameters() as $param) {
            $params[] = $param->getValue()->materialize();
        }

        $method = $methodInjection->getMethod();
        $method->setAccessible(true);
        $method->invokeArgs($instance, $params);
    }

    /**
     * @param mixed           $instance
     * @param MethodInjection $propertyInjection
     */
    private function injectForProperty($instance, PropertyInjection $propertyInjection)
    {
        $value = $propertyInjection->getValue()->materialize();
        $propery = $propertyInjection->getProperty();
        $propery->setAccessible(true);
        $propery->setvalue($instance, $value);
    }
}
