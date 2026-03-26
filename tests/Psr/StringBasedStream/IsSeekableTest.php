<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class IsSeekableTest extends TestCase
{
    /**
     * Tests receiving bool when using isSeekable() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::isSeekable
     */
    public function testIsSeekableReturnsTrue(): void
    {
        $stream = StringBasedStream::createFromString('');

        TestCase::assertTrue($stream->isSeekable());
    }

    /**
     * Tests receiving bool when using isSeekable() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::isSeekable
     */
    public function testIsSeekableReturnsFalseAfterClose(): void
    {
        $stream = StringBasedStream::createFromString('');
        $stream->close();

        TestCase::assertFalse($stream->isSeekable());
    }
}
