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
     * @var DependencyInterface[]
     */
    private $parameters;

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
     * @param DefinitionInterface[] $parameters
     * @return BindingDefinition
     */
    public function with(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(ContainerInterface $container)
    {
        $injectionFinder = $container->getInjectionFinder();
        $function = ReflectionUtils::getFunction($this->factory);

        if ($this->factory instanceof \Closure) {
            $factory = new SerializableClosure($this->factory);
        } else {
            $factory = $this->factory;
        }

        if ($this->parameters !== null) {
            $parameters = [];
            foreach ($this->parameters as $definition) {
                $parameters[] = $definition->get($container);
            }
        } else {
            $parameters = $injectionFinder->getParameterDependencies($function);
        }

        return new FactoryDependency($this->key, $factory, $parameters);
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveScope(ContainerInterface $container)
    {
        return PrototypeScope::getInstance();
    }
}
