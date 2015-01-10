<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\Container;
use Emonkak\Di\Scope\ScopeInterface;
use Emonkak\Di\Value\PrototypeValue;

class BindingDefinition extends AbstractDefinition
{
    /**
     * @var \ReflectionClass
     */
    private $target;

    /**
     * @param string $target
     */
    public function __construct(\ReflectionClass $target)
    {
        $this->target = $target;
    }

    /**
     * @param string $target
     */
    public function to($target)
    {
        return $this->toReflection(new \ReflectionClass($target));
    }

    /**
     * @param \ReflectionClass $target
     * @return BindingDefinition
     */
    public function toReflection(\ReflectionClass $target)
    {
        if (!$target->isSubclassOf($this->target)) {
            throw new \InvalidArgumentException(
                sprintf('"%s" is not sub-class of "%s".', $target->getName(), $this->target->getName())
            );
        }
        $this->target = $target;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function resolve(Container $container)
    {
        $injectionPolicy = $container->getInjectionPolicy();
        if (!$injectionPolicy->isInjectableClass($this->target)) {
            throw new \LogicException(
                sprintf('The class "%s" is not injectable.', $this->target->getName())
            );
        }

        $injectionFinder = $container->getInjectionFinder();
        return new PrototypeValue(
            $this->target,
            $injectionFinder->getConstructorInjection($this->target),
            $injectionFinder->getMethodInjections($this->target),
            $injectionFinder->getPropertyInjections($this->target)
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveScope(Container $container)
    {
        $injectionPolicy = $container->getInjectionPolicy();
        return $injectionPolicy->getScope($this->target);
    }
}
