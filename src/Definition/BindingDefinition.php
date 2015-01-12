<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\Container;
use Emonkak\Di\Scope\ScopeInterface;
use Emonkak\Di\Value\ObjectValue;

class BindingDefinition extends AbstractDefinition
{
    /**
     * @var string
     */
    private $target;

    /**
     * @param string $target
     */
    public function __construct($target)
    {
        $this->target = $target;
    }

    /**
     * @param string $target
     */
    public function to($target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function resolve(Container $container)
    {
        $class = new \ReflectionClass($this->target);
        $injectionPolicy = $container->getInjectionPolicy();

        if (!$injectionPolicy->isInjectableClass($class)) {
            throw new \LogicException(
                sprintf('Class "%s" is not injectable.', $this->target->getName())
            );
        }

        $injectionFinder = $container->getInjectionFinder();
        return new ObjectValue(
            $class->getName(),
            $injectionFinder->getConstructorInjection($class),
            $injectionFinder->getMethodInjections($class),
            $injectionFinder->getPropertyInjections($class)
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveScope(Container $container)
    {
        $class = new \ReflectionClass($this->target);
        $injectionPolicy = $container->getInjectionPolicy();
        return $injectionPolicy->getScope($class);
    }
}
