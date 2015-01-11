<?php

namespace Emonkak\Di\Value;

class CachedValue implements InjectableValueInterface
{
    private $value;

    private $cache;

    private $isCached = false;

    /**
     * @param InjectableValueInterface $value
     */
    public function __construct(InjectableValueInterface $value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function accept(InjectableValueVisitorInterface $visitor)
    {
        return $visitor->visitValue($this);
    }

    /**
     * {@inheritDoc}
     */
    public function materialize()
    {
        if (!$this->isCached) {
            $this->isCached = true;
            $this->cache = $this->value->materialize();
        }
        return $this->cache;
    }

    /**
     * @see \Serializable
     * @return string
     */
    public function __sleep()
    {
        return ['value'];
    }
}
