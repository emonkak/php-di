<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Scope\PrototypeScope;

class AliasDefinition extends AbstractDefinition
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
     * {@inheritDoc}
     */
    protected function resolveDependency(ContainerInterface $container)
    {
        return $container->resolve($this->target);
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveScope(ContainerInterface $container)
    {
        return PrototypeScope::getInstance();
    }
}
