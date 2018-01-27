<?php

namespace Emonkak\Di;

use Interop\Container\ContainerInterface as InteropContainerInterface;
use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface, InteropContainerInterface
{
    /**
     * @param string $key
     * @param mixed  $value
     */
    public function store($key, $value);

    /**
     * @param string $key
     * @return boolean
     */
    public function isStored($key);
}
