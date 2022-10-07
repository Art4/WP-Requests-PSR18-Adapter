<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class IsReadableTest extends TestCase
{
    /**
     * Tests receiving false when using isReadable() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::isReadable
     *
     * @return void
     */
    public function testIsReadableReturnsFalse()
    {
        $stream = StringBasedStream::createFromString('');

        $this->assertFalse($stream->isReadable());
    }
}
