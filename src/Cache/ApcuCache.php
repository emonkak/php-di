<?php

namespace Emonkak\Di\Cache;

/**
 * @implements \ArrayAccess<string,?mixed>
 */
class ApcuCache implements \ArrayAccess
{
    private string  $prefix;

    private int $lifetime;

    public function __construct(string $prefix = '', int $lifetime = 0)
    {
        $this->prefix = $prefix;
        $this->lifetime = $lifetime;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        $value = apcu_fetch($this->prefix . $offset, $success);
        return $success ? $value : null;
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
