<?php

namespace Emonkak\Di;

use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\Injection\MethodInjection;
use Emonkak\Di\Injection\ParameterInjection;
use Emonkak\Di\Injection\PropertyInjection;
use Emonkak\Di\ValueResolver\ValueResolverInterface;

class InjectionFinder
{
    private $valueResolver;
    private $injectionPolicy;

    /**
     * @param ValueResolverInterface   $valueResolver
     * @param InjectionPolicyInterface $injectionPolicy
     */
    public function __construct(
        ValueResolverInterface $valueResolver,
        InjectionPolicyInterface $injectionPolicy
    ) {
        $this->valueResolver = $valueResolver;
        $this->injectionPolicy = $injectionPolicy;
    }

    /**
     * @param \ReflectionClass $class
     * @return MethodInjection|null
     */
    public function getConstructorInjection(\ReflectionClass $class)
    {
        $constructor = $class->getConstructor();
        return $constructor ? $this->createMethodInjection($constructor) : null;
    }

    /**
     * @param \ReflectionClass $class
     * @return MethodInjection[]
     */
    public function getMethodInjections(\ReflectionClass $class)
    {
        $injections = [];

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

        $properties = $this->injectionPolicy->getInjectableProperties($class);
        foreach ($properties as $property) {
            $injections[] = $this->createPropertyInjection($property);
        }

        return $injections;
    }

    /**
     * @param \ReflectionFunctionAbstract $function
     * @return InjectableValueInterface
     */
    public function getParameters(\ReflectionFunctionAbstract $function)
    {
        $params = [];
        foreach ($function->getParameters() as $param) {
            $value = $this->valueResolver->getParameterValue($param);
            if ($value === null) {
                throw new \LogicException(sprintf(
                    'Parameter "$%s" of "%s()" can not be resolved.',
                    $param->getName(),
                    $function->getName()
                ));
            }
            $params[] = $value;
        }
        return $params;
    }

    /**
     * @param \ReflectionMethod $mehtod
     * @return MethodInjection
     */
    private function createMethodInjection(\ReflectionMethod $method)
    {
        $params = $this->getParameters($method);
        return new MethodInjection($method->getName(), $params);
    }

    /**
     * @param \ReflectionMethod $mehtod
     * @return PropertyInjection
     */
    private function createPropertyInjection(\ReflectionProperty $property)
    {
        $value = $this->valueResolver->getPropertyValue($property);
        if ($value === null) {
            throw new \LogicException(sprintf(
                'Property "%s::$%s" can not be resolved.',
                $property->getDeclaringClass()->getName(),
                $property->getName()
            ));
        }
        return new PropertyInjection($property->getName(), $value);
    }
}
