<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\Dependency\DependencyFinders;
use Emonkak\Di\Dependency\FactoryDependency;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;
use Emonkak\Di\ResolverInterface;
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
     * @param stirng    $key
     * @param callable $factory
     */
    public function __construct($key, callable $factory)
    {
        $this->key = $key;
        $this->factory = $factory;
    }

    /**
     * @param DefinitionInterface[] $parameters
     * @return $this
     */
    public function with(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveDependency(ResolverInterface $resolver, InjectionPolicyInterface $injectionPolicy)
    {
        if ($this->factory instanceof \Closure) {
            $factory = new SerializableClosure($this->factory);
        } else {
            $factory = $this->factory;
        }

        $dependencies = [];
        if ($this->parameters !== null) {
            foreach ($this->parameters as $definition) {
                $dependencies[] = $definition->resolveBy($resolver, $injectionPolicy);
            }
        } else {
            $function = ReflectionUtils::getFunction($this->factory);
            foreach ($function->getParameters() as $parameter) {
                $dependencies[] = $resolver->resolveParameter($parameter);
            }
        }

        return new FactoryDependency($this->key, $factory, $dependencies);
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveScope(ResolverInterface $resolver, InjectionPolicyInterface $injectionPolicy)
    {
        return PrototypeScope::getInstance();
    }
}
