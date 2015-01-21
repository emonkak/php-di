<?php

namespace Emonkak\Di\Dependency;

use Emonkak\Di\Container;
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
    public function accept(DependencyVistorInterface $visitor)
    {
        return $visitor->visitReferenceDependency($this);
    }

    /**
     * {@inheritDoc}
     */
    public function inject(\ArrayAccess $valueBag)
    {
        return $valueBag[$this->key];
    }

    /**
     * {@inheritDoc}
     */
    public function get(Container $container)
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
}
