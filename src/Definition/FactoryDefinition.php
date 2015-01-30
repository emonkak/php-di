<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\Scope\PrototypeScope;
use Emonkak\Di\Scope\ScopeInterface;
use Emonkak\Di\Utils\ReflectionUtils;
use SuperClosure\SerializableClosure;

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
    public function resolve(ContainerInterface $container)
    {
        $finder = $container->getInjectionFinder();
        $function = ReflectionUtils::getFunction($this->factory);

        if ($this->factory instanceof \Closure) {
            $factory = new SerializableClosure($this->factory);
        } else {
            $factory = $this->factory;
        }

        return new FactoryDependency(
            $this->key,
            $factory,
            $finder->getParameterDependencies($function)
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveScope(ContainerInterface $container)
    {
        return PrototypeScope::getInstance();
    }
}
