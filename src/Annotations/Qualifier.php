<?php

namespace Emonkak\Di\Annotations;

/**
 * @Annotation
 * @Target({"METHOD","PROPERTY"})
 */
class Qualifier
{
    /**
     * @var array
     */
    private $values;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getValue($name)
    {
        return isset($this->values[$name]) ? ltrim($this->values[$name], '\\') : null;
    }

    /**
     * @return string
     */
    public function getSingleValue()
    {
        return $this->getValue('value');
    }
}
