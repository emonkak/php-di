<?php

namespace Emonkak\Di\Binding;

use Emonkak\Di\Container;

class AliasBinding implements BindingInterface
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
    public function toInjectableValue(Container $container)
    {
        return $container->get($this->target);
    }
}
