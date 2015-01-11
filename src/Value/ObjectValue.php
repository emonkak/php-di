<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Injection\MethodInjection;
use Emonkak\Di\Injection\PropertyInjection;
use Emonkak\Di\Injector;

class ObjectValue implements ObjectValueInterface
{
    private $className;

    private $constructorInjection;

    private $methodInjections;

    private $propertyInjections;

    /**
     * @param string               $className
     * @param MethodInjection|null $constructorInjection
     * @param MethodInjection[]    $methodInjections
     * @param PropertyInjection[]  $propertyInjections
     */
    public function __construct(
        $className,
        MethodInjection $constructorInjection = null,
        array $methodInjections = [],
        array $propertyInjections = []
    ) {
        $this->className = $className;
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
    public function inject()
    {
        $class = new \ReflectionClass($this->className);
        $instance = $class->newInstanceWithoutConstructor();

        if ($this->constructorInjection) {
            $this->injectForMethod($class, $instance, $this->constructorInjection);
        }

        foreach ($this->methodInjections as $methodInjection) {
            $this->injectForMethod($class, $instance, $methodInjection);
        }

        foreach ($this->propertyInjections as $propertyInjection) {
            $this->injectForProperty($class, $instance, $propertyInjection);
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
    public function getConstructorInjection()
    {
        return $this->constructorInjection;
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
     * @param \ReflectionClass $class
     * @param mixed            $instance
     * @param MethodInjection  $methodInjection
     */
    private function injectForMethod(\ReflectionClass $class, $instance, MethodInjection $methodInjection)
    {
        $args = [];
        foreach ($methodInjection->getParameters() as $parameter) {
            $args[] = $parameter->inject();
        }

        $method = $class->getMethod($methodInjection->getMethodName());
        $method->setAccessible(true);
        $method->invokeArgs($instance, $args);
    }

    /**
     * @param \ReflectionClass  $class
     * @param mixed             $instance
     * @param PropertyInjection $propertyInjection
     */
    private function injectForProperty(\ReflectionClass $class, $instance, PropertyInjection $propertyInjection)
    {
        $value = $propertyInjection->getValue()->inject();
        $propery = $class->getProperty($propertyInjection->getPropertyName());
        $propery->setAccessible(true);
        $propery->setvalue($instance, $value);
    }
}
