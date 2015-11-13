<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Definition\DefinitionInterface;
use Emonkak\Di\InjectionPolicy\InjectionPolicyInterface;

class ReferenceDependency implements DefinitionInterface, DependencyInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * {@inheritDoc}
     */
    public function accept(DependencyVisitorInterface $visitor)
    {
        return $visitor->visitReferenceDependency($this);
    }

    /**
     * {@inheritDoc}
     */
    public function resolveBy(ContainerInterface $container, InjectionPolicyInterface $injectionPolicy)
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * {@inheritDoc}
     */
    public function materializeBy(ContainerInterface $container)
    {
        return $container->get($this->key);
    }

    /**
     * {@inheritDoc}
     */
    public function isSingleton()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function traverse(callable $callback)
    {
        $callback($this, $this->key);
    }
}
