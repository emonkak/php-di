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
     * @param ObjectValueInterface $value
     * @return mixed
     */
    public function visitObjectValue(ObjectValueInterface $value);
}
