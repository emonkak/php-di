<?php

namespace Emonkak\Di\Exception;

use Interop\Container\Exception\ContainerException as InteropContainerException;
use Psr\Container\ContainerExceptionInterface as PsrContainerExceptionInterface;

/**
 * @internal
 */
class UninjectableClassException extends \RuntimeException implements PsrContainerExceptionInterface, InteropContainerException
{
}
