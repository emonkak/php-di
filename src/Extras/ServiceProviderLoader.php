<?php

namespace Emonkak\Di\Extras;

/**
 * The service provider loader on a cache.
 */
class ServiceProviderLoader implements ServiceProviderLoaderInterface
{
    /**
     * @var \ArrayAccess
     */
    private $cache;

    /**
     * @return ServiceProviderLoader
     */
    public static function create()
    {
        return new ServiceProviderLoader(new \ArrayObject());
    }

    /**
     * @param \ArrayAccess $cache
     */
    public function __construct(\ArrayAccess $cache)
    {
        $this->cache = $cache;
    }

    /**
     * {@inheritDoc}
     */
    public function load($className)
    {
        if (!isset($this->cache[$className])) {
            throw new \RuntimeException(
                sprintf('Failed to load "%s" because the cache does not exist.', $className)
            );
        }

        eval($this->cache[$className]);
    }

    /**
     * {@inheritDoc}
     */
    public function canLoad($className)
    {
        return isset($this->cache[$className]);
    }

    /**
     * {@inheritDoc}
     */
    public function write($className, $source)
    {
        $this->cache[$className] = $source;
    }
}
