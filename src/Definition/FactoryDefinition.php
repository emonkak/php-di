<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\Container;
use Emonkak\Di\Scope\ScopeInterface;
use Emonkak\Di\Scope\SingletonScope;
use Emonkak\Di\Value\LazyValue;

class FactoryDefinition extends AbstractDefinition
{
    private $factory;

    /**
     * @param callable $factory
     */
    public function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(Container $container)
    {
        $finder = $container->getInjectionFinder();
        $function = $this->getFunction();

        return new LazyValue(
            $this->factory,
            $finder->getParameters($function)
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveScope(Container $container)
    {
        return SingletonScope::getInstance();
    }

    /**
     * @return \ReflectionFunctionAbstract
     */
    private function getFunction()
    {
        if (is_array($this->factory)) {
            return new \ReflectionMethod($this->factory[0], $this->factory[1]);
        }

        if (is_object($this->factory) && !($this->factory instanceof \Closure)) {
            return new \ReflectionMethod($this->factory, '__invoke');
        }

        return new \ReflectionFunction($this->factory);
    }
}
