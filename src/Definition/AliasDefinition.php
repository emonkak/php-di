<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\Container;
use Emonkak\Di\Scope\PrototypeScope;

class AliasDefinition extends AbstractDefinition
{
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
    protected function resolve(Container $container)
    {
        return $container->get($this->target);
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveScope(Container $container)
    {
        return PrototypeScope::getInstance();
    }
}
