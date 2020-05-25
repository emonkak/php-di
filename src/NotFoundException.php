<?php

namespace Emonkak\Di;

use Psr\Container\NotFoundExceptionInterface;

/**
 * @internal
 */
class NotFoundException extends \RuntimeException implements NotFoundExceptionInterface
{
    public static function noEntryKey(string $key): self
    {
        return new self("No entry found for key `$key`.");
    }
}
