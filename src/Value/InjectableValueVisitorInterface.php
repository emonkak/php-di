<?php

namespace Emonkak\Di\Value;

interface InjectableValueVisitorInterface
{
    /**
     * @param InjectableValueInterface $value
     * @return mixed
     */
    public function visitValue(InjectableValueInterface $value);

    /**
     * @param ObjectValue $value
     * @return mixed
     */
    public function visitObjectValue(ObjectValue $value);

    /**
     * @param UndefinedValue $value
     * @return mixed
     */
    public function visitUndefinedValue(UndefinedValue $value);
}
