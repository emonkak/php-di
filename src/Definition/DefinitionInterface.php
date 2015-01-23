<?php

namespace Emonkak\Di\Definition;

use Emonkak\Di\ContainerInterface;
use Emonkak\Di\Value\InjectableValueInterface;

interface DefinitionInterface
{
    /**
     * @param ContainerInterface $container
     * @return InjectableValueInterface
     */
    public function get(ContainerInterface $container);
}
