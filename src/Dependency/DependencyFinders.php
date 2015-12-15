<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\Exception\NotFoundException;
use Interop\Container\Exception\NotFoundException as InteropNotFoundException;

class DependencyFinders
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * @param ContainerInterface       $container
     * @param InjectionPolicyInterface $injectionPolicy
     * @param \ReflectionClass         $class
     * @return DependencyInterface[]
     */
    public static function getConstructorDependencies(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy, \ReflectionClass $class)
    {
        $constructor = $class->getConstructor();
        return $constructor ? self::getParameterDependencies($container, $injectionPolicy, $constructor) : [];
    }

    /**
     * @param ContainerInterface       $container
     * @param InjectionPolicyInterface $injectionPolicy
     * @param \ReflectionClass         $class
     * @return DependencyInterface[]
     */
    public static function getMethodDependencies(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy, \ReflectionClass $class)
    {
        $injections = [];

        $methods = $injectionPolicy->getInjectableMethods($class);
        foreach ($methods as $method) {
            $injections[$method->name] = self::getParameterDependencies($container, $injectionPolicy, $method);
        }

        return $injections;
    }

    /**
     * @param ContainerInterface       $container
     * @param InjectionPolicyInterface $injectionPolicy
     * @param \ReflectionClass         $class
     * @return DependencyInterface[]
     */
    public static function getPropertyDependencies(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy, \ReflectionClass $class)
    {
        $injections = [];

        $properties = $injectionPolicy->getInjectableProperties($class);
        foreach ($properties as $property) {
            $injections[$property->name] = self::resolvePropertyDependency($container, $injectionPolicy, $property);
        }

        return $injections;
    }

    /**
     * @param ContainerInterface          $container
     * @param InjectionPolicyInterface    $injectionPolicy
     * @param \ReflectionFunctionAbstract $function
     * @return DependencyInterface[]
     */
    public static function getParameterDependencies(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy, \ReflectionFunctionAbstract $function)
    {
        $dependencies = [];
        foreach ($function->getParameters() as $param) {
            $dependencies[] = self::resolveParameterDependency($container, $injectionPolicy, $param);
        }
        return $dependencies;
    }

    /**
     * @param ContainerInterface       $container
     * @param InjectionPolicyInterface $injectionPolicy
     * @param \ReflectionParameter     $param
     * @return DependencyInterface
     */
    public static function resolveParameterDependency(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy, \ReflectionParameter $parameter)
    {
        $key = $injectionPolicy->getParameterKey($parameter);
        try {
            return $container->resolve($key);
        } catch (InteropNotFoundException $e) {
            if ($parameter->isOptional()) {
                $defaultValue = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null;
                return new ValueDependency($defaultValue);
            }
            throw NotFoundException::ofParameter($key, $parameter, $e);
        }
    }

    /**
     * @param ContainerInterface       $container
     * @param InjectionPolicyInterface $injectionPolicy
     * @param \ReflectionProperty      $property
     * @return DependencyInterface
     */
    public static function resolvePropertyDependency(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy, \ReflectionProperty $property)
    {
        $key = $injectionPolicy->getPropertyKey($property);
        try {
            return $container->resolve($key);
        } catch (InteropNotFoundException $e) {
            $class = $property->getDeclaringClass();
            $values = $class->getDefaultProperties();
            if (isset($values[$property->name])) {
                return new ValueDependency($values[$property->name]);
            }
            throw NotFoundException::ofProperty($key, $property, $e);
        }
    }
}
