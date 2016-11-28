<?php

namespace Emonkak\Di;

use Interop\Container\ContainerInterface as InteropContainerInterface;

interface ContainerInterface extends InteropContainerInterface
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
