<?php

namespace Emonkak\Di\Annotation;

use Emonkak\Di\Scope\PrototypeScope;
use Emonkak\Di\Scope\ScopeInterface;
use Emonkak\Di\Scope\SingletonScope;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Scope
{
    const PROTOTYPE = 'PROTOTYPE';
    const SINGLETON = 'SINGLETON';

    /**
     * @Enum({"PROTOTYPE", "SINGLETON"})
     */
    public $value;

    /**
     * @return ScopeInterface
     */
    public function getScope()
    {
        switch ($this->value) {
        case self::PROTOTYPE:
            return PrototypeScope::getInstance();
        case self::SINGLETON:
            return SingletonScope::getInstance();
        }
    }
}
