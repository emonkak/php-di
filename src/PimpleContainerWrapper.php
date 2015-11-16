<?php

namespace Emonkak\Di;

use Pimple\Container as Pimple;

class PimpleContainerWrapper implements \ArrayAccess
{
    /**
     * @var Pimple
     */
    private $container;

    /**
     * @param Pimple $container
     */
    public function __construct(Pimple $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($key)
    {
        return $this->container[$key];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($key)
    {
        return isset($this->container[$key]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($key, $value)
    {
        if (method_exists($value, '__invoke')) {
            $this->container[$key] = $this->container->protect($value);
        } else {
            $this->container[$key] = $value;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($key)
    {
        unset($this->container[$key]);
    }
}
