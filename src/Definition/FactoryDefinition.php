<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\Container;
use Emonkak\Di\Scope\ScopeInterface;
use Emonkak\Di\Scope\SingletonScope;
use Emonkak\Di\Utils\ReflectionUtils;
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
        $function = ReflectionUtils::getFunction($this->factory);

        return new LazyValue(
            $this->factory,
            $finder->getParameterValues($function)
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveScope(Container $container)
    {
        return SingletonScope::getInstance();
    }
}
