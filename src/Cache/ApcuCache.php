<?php

namespace Emonkak\Di\Cache;

/**
 * Provides the cache from APCu.
 */
class ApcuCache implements \ArrayAccess
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
        return apcu_fetch($this->prefix . $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return apcu_exists($this->prefix . $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        apcu_store($this->prefix . $offset, $value, $this->lifetime);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        apcu_delete($this->prefix . $offset);
    }
}
