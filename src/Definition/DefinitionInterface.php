<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\Container;
use Emonkak\Di\Value\InjectableValueInterface;

interface DefinitionInterface
{
    /**
     * @param Container $container
     * @return InjectableValueInterface
     */
    public function get(Container $container);
}
