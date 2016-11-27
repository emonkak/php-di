<?php

namespace Emonkak\Di\Exception;

use Interop\Container\Exception\ContainerException;

/**
 * @internal
 */
class UninjectableClassException extends \RuntimeException implements ContainerException
{
}
