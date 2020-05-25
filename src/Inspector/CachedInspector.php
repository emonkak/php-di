<?php

declare(strict_types=1);

namespace Emonkak\Di\Inspector;

/**
 * @template TDependency
 * @implements InspectorInterface<TDependency>
 */
class CachedInspector implements InspectorInterface
{
    /**
     * @var InspectorInterface<TDependency>
     */
    private InspectorInterface $inspector;

    /**
     * @var \ArrayAccess<string,TDependency>
     */
    private \ArrayAccess $cache;

    /**
     * @param InspectorInterface<TDependency> $inspector
     * @param \ArrayAccess<string,TDependency> $cache
     */
    public function __construct(InspectorInterface $inspector, \ArrayAccess $cache)
    {
        $this->inspector = $inspector;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function inspect(string $key, array $bindings)
    {
        if (isset($this->cache[$key])) {
            $dependency = $this->cache[$key];
        } else {
            $dependency = $this->inspector->inspect($key, $bindings);
            $this->cache[$key] = $dependency;
        }
        return $dependency;
    }

    /**
     * @return \ArrayAccess<string,TDependency>
     */
    public function getCache(): \ArrayAccess
    {
        return $this->cache;
    }
}
