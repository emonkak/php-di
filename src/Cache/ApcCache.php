<?php

namespace Emonkak\Di\Cache;

/**
 * Provides the cache from APC.
 */
class ApcCache implements \ArrayAccess
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var integer
     */
    private $lifetime;

    /**
     * @param string  $prefix   The string to be prepended to a key.
     * @param integer $lifetime The time until a cache expiration.
     */
    public function __construct($prefix = '', $lifetime = 0)
    {
        $this->prefix = $prefix;
        $this->lifetime = $lifetime;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return apc_fetch($this->prefix . $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return apc_exists($this->prefix . $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        apc_store($this->prefix . $offset, $value, $this->lifetime);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        apc_delete($this->prefix . $offset);
    }
}
