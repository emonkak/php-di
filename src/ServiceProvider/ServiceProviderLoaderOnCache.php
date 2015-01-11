<?php

namespace Emonkak\Di\ServiceProvider;

use Doctrine\Common\Cache\Cache;

/**
 * The service provider loader on a cache.
 */
class ServiceProviderLoaderOnCache implements ServiceProviderLoaderInterface
{
    /**
     * @var Cache $cahce
     */
    private $cache;

    /**
     * @param Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * {@inheritDoc}
     */
    public function load($className)
    {
        $source = $this->cache->fetch($className);

        if ($source === false) {
            throw new \RuntimeException(
                sprintf('Failed to load "%s" because the cache does not exist.', $className)
            );
        }

        eval($source);
    }

    /**
     * {@inheritDoc}
     */
    public function canLoad($className)
    {
        return $this->cache->contains($className);
    }

    /**
     * {@inheritDoc}
     */
    public function write($className, $source)
    {
        $result = $this->cache->save($className, $source);

        if (!$result) {
            throw new \RuntimeException('Failed to store the source to the cache.');
        }
    }
}
