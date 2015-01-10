<?php

namespace Emonkak\Di\Annotations;

/**
 * @Annotation
 * @Target("ALL")
 */
class Inject
{
    /**
     * @var bool
     */
    public $value = true;

    /**
     * @return boolean
     */
    public function isInjectable()
    {
        return $this->value;
    }
}
