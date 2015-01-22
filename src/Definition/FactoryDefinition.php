<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\Container;
use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\Scope\ScopeInterface;
use Emonkak\Di\Scope\SingletonScope;
use Emonkak\Di\Utils\ReflectionUtils;

class FactoryDefinition extends AbstractDefinition
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var callable
     */
    private $factory;

    /**
     * @param stirng $key
     * @param callable $factory
     */
    public function __construct($key, callable $factory)
    {
        $this->key = $key;
        $this->factory = $factory;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(Container $container)
    {
        $finder = $container->getInjectionFinder();
        $function = ReflectionUtils::getFunction($this->factory);

        return new FactoryDependency(
            $this->key,
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
