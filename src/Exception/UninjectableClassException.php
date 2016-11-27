<?php

namespace Emonkak\Di\Exception;

use Interop\Container\Exception\ContainerException;

class UninjectableClassException extends \RuntimeException implements ContainerException
{
}
