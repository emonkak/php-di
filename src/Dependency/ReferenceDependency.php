<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Definition\DefinitionInterface;

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
    public function acceptVisitor(DependencyVistorInterface $visitor)
    {
        return $visitor->visitReferenceDependency($this);
    }

    /**
     * {@inheritDoc}
     */
    public function acceptTraverser(DependencyTraverserInterface $traverser)
    {
        yield $this->getKey() => $traverser->map($this);
    }

    /**
     * {@inheritDoc}
     */
    public function inject(ContainerInterface $container)
    {
        return $container->getInstance($this->key);
    }

    /**
     * {@inheritDoc}
     */
    public function get(ContainerInterface $container)
    {
        return $this;
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
    public function isSingleton()
    {
        return true;
    }
}
