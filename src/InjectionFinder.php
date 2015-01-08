<?php

namespace Emonkak\Di;

use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\Injection\MethodInjection;
use Emonkak\Di\Injection\PropertyInjection;
use Emonkak\Di\ValueResolver\ValueResolverInterface;

class InjectionFinder
{
    private $valueResolver;
    private $injectionPolilcy;

    /**
     * @param ValueResolverInterface   $valueResolver
     * @param InjectionPolicyInterface $injectionPolilcy
     */
    public function __construct(
        ValueResolverInterface $valueResolver,
        InjectionPolicyInterface $injectionPolilcy
    ) {
        $this->valueResolver = $valueResolver;
        $this->injectionPolicy = $injectionPolilcy;
    }

    /**
     * @param \ReflectionClass $class
     * @return MethodInjection[]
     */
    public function getMethodInjections(\ReflectionClass $class)
    {
        $injections = [];

        $constructor = $this->injectionPolicy->getConstructor($class);
        if ($constructor) {
            $injections[] = $this->createMethodInjection($constructor);
        }

        $methods = $this->injectionPolicy->getInjectableMethods($class);
        foreach ($methods as $method) {
            $injections[] = $this->createMethodInjection($method);
        }

        return $injections;
    }

    /**
     * @param \ReflectionClass $class
     * @return PropertyInjection[]
     */
    public function getPropertyInjections(\ReflectionClass $class)
    {
        $injections = [];

        $properties = $this->injectionPolicy->getInjectableMethods($class);
        foreach ($properties as $property) {
            $injections[] = $this->createPropertyInjection($property);
        }

        return $injections;
    }

    /**
     * @param \ReflectionMethod $mehtod
     * @return MethodInjection
     */
    private function createMethodInjection(\ReflectionMethod $method)
    {
        $values = [];
        foreach ($method->getParameters() as $param) {
            $value = $this->valueResolver->getParameterValue($param);
            if ($value === null) {
                throw new \LogicException('The parameter can not be resolved.');
            }
            $values[] = $value;
        }
        return new MethodInjection($method, $values);
    }

    /**
     * @param \ReflectionMethod $mehtod
     * @return PropertyInjection
     */
    private function createPropertyInjection(\ReflectionProperty $property)
    {
        $value = $this->valueResolver->getPropertyValue($property);
        if ($value === null) {
            throw new \LogicException('The parameter can not be resolved.');
        }
        return new PropertyInjection($property, $value);
    }
}
