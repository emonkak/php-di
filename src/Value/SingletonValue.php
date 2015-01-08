<?php

namespace Emonkak\Di\Value;

class SingletonValue implements InjectableValueInterface
{
    private $source;
    private $cache;
    private $isCached = false;

    /**
     * @param InjectableValueInterface $source
     */
    public function __construct(InjectableValueInterface $source)
    {
        $this->source = $source;
    }

    /**
     * {@inheritDoc}
     */
    public function materialize()
    {
        if (!$this->isCached) {
            $this->isCached = true;
            $this->cache = $this->source->materialize();
        }
        return $this->cache;
    }
}
