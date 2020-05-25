<?php

declare(strict_types=1);

namespace Emonkak\Di\Tests;

use Emonkak\Di\NotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Emonkak\Di\NotFoundException
 */
class NotFoundExceptionTest extends TestCase
{
    public function testNoEntryKey(): void
    {
        $key = 'key';
        $exception = NotFoundException::noEntryKey($key);

        $this->assertSame("No entry found for key `$key`.", $exception->getMessage());
    }
}
