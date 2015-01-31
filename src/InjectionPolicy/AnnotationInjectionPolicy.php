<?php

namespace Emonkak\Di\InjectionPolicy;

use Composer\Autoload\ClassLoader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use Emonkak\Di\Annotation\Inject;
use Emonkak\Di\Annotation\Qualifier;
use Emonkak\Di\Annotation\Scope;
use Emonkak\Di\Scope\SingletonScope;

class AnnotationInjectionPolicy implements InjectionPolicyInterface
{
    private static $isRegistered = false;

    private $fallback;

    private $reader;

    /**
     * @return AnnotationInjectionPolicy
     */
    public static function create()
    {
        return new self(new DefaultInjectionPolicy(), new AnnotationReader());
    }

    private static function registerLoader()
    {
        if (!self::$isRegistered) {
            self::$isRegistered = true;
            $loader = new ClassLoader();
            $loader->addPsr4('Emonkak\\Di\\Annotation\\', realpath(__DIR__ . '/../Annotation'));
            AnnotationRegistry::registerLoader([$loader, 'loadClass']);
        }
    }

    /**
     * @param InjectionPolicyInterface $fallback
     * @param Reader                   $reader
     */
    public function __construct(InjectionPolicyInterface $fallback, Reader $reader)
    {
        self::registerLoader();

        $this->fallback = $fallback;
        $this->reader = $reader;
    }

    /**
     * {@inheritDoc}
     */
    public function getInjectableMethods(\ReflectionClass $class)
    {
        $methods = $this->fallback->getInjectableMethods($class);

        foreach ($class->getMethods() as $method) {
            if ($this->isInjectableMethod($method)) {
                $methods[] = $method;
            }
        }

        return array_unique($methods);
    }

    /**
     * {@inheritDoc}
     */
    public function getInjectableProperties(\ReflectionClass $class)
    {
        $properties = $this->fallback->getInjectableProperties($class);

        foreach ($class->getProperties() as $propery) {
            if ($this->isInjectableProperty($propery)) {
                $properties[] = $propery;
            }
        }

        return array_unique($properties);
    }

    /**
     * {@inheritDoc}
     */
    public function getParameterKey(\ReflectionParameter $param)
    {
        $function = $param->getDeclaringFunction();

        if ($function instanceof \ReflectionMethod) {
            foreach ($this->reader->getMethodAnnotations($function) as $annotation) {
                if ($annotation instanceof Qualifier) {
                    $key = $annotation->getValue($param->name);

                    if ($key !== null) {
                        return $key;
                    }
                }
            }
        }

        return $this->fallback->getParameterKey($param);
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyKey(\ReflectionProperty $prop)
    {
        foreach ($this->reader->getPropertyAnnotations($prop) as $annotation) {
            if ($annotation instanceof Qualifier) {
                $key = $annotation->getSingleValue();

                if ($key !== null) {
                    return $key;
                }
            }
        }

        return $this->fallback->getPropertyKey($prop);
    }

    /**
     * {@inheritDoc}
     */
    public function getScope(\ReflectionClass $class)
    {
        foreach ($this->reader->getClassAnnotations($class) as $annotation) {
            if ($annotation instanceof Scope) {
                return $annotation->getScope();
            }
        }
        return $this->fallback->getScope($class);
    }

    /**
     * {@inheritDoc}
     */
    public function isInjectableClass(\ReflectionClass $class)
    {
        foreach ($this->reader->getClassAnnotations($class) as $annotation) {
            if ($annotation instanceof Inject) {
                return true;
            }
        }
        return $this->fallback->isInjectableClass($class);
    }

    /**
     * @param \ReflectionMethod $method
     * @return boolean
     */
    private function isInjectableMethod(\ReflectionMethod $method)
    {
       foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof Inject) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param \ReflectionMethod $method
     * @return boolean
     */
    private function isInjectableProperty(\ReflectionProperty $prop)
    {
       foreach ($this->reader->getPropertyAnnotations($prop) as $annotation) {
            if ($annotation instanceof Inject) {
                return true;
            }
        }
        return false;
    }
}
