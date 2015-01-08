<?php

namespace Emonkak\Di\Value;

use Emonkak\Di\Injector;

class UndefinedValue implements InjectableValueInterface
{
    private $tag;

    /**
     * @param string $tag
     */
    public function __construct($tag)
    {
        $this->tag = $tag;
    }

    /**
     * {@inheritDoc}
     */
    public function accept(InjectableValueVisitorInterface $visitor)
    {
        return $visitor->visitUndefinedValue($this);
    }

    /**
     * @return mixed
     */
    public function materialize()
    {
        return new \RuntimeException('This value can not be materialized.');
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }
}
