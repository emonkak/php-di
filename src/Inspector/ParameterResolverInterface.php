<?php

declare(strict_types=1);

namespace Emonkak\Di\Inspector;

interface ParameterResolverInterface
{
    public function resolveKey(\ReflectionParameter $parameter): string;

    public function resolveClass(\ReflectionParameter $parameter): ?\ReflectionClass;
}
