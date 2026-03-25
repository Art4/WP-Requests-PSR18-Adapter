<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use InvalidArgumentException;
use RuntimeException;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class ReadTest extends TestCase
{
    /**
     * Tests using read() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::read
     */
    public function testReadWithNegativeLengthThrowsRuntimeException(): void
    {
        $stream = StringBasedStream::createFromString('');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Length must be non-negative.');

        $stream->read(-1);
    }

    /**
     * Tests using read() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::read
     */
    public function testReadWithZeroLengthReturnsEmptyString(): void
    {
        $stream = StringBasedStream::createFromString('');

        TestCase::assertSame('', $stream->read(0));
    }

    /**
     * Tests using read() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::read
     */
    public function testReadWithLengthReturnsString(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        TestCase::assertSame('01234', $stream->read(5));
    }

    /**
     * Tests using read() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::read
     */
    public function testReadMultipleTimesReturnsString(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        TestCase::assertSame('0123', $stream->read(4));
        TestCase::assertSame('4567', $stream->read(4));
        TestCase::assertSame('89', $stream->read(4));
    }

    /**
     * Tests using read() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::read
     */
    public function testReadWithLargeLengthReturnsString(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        TestCase::assertSame('0123456789', $stream->read(500000));
    }

    /**
     * Tests using read() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::read
     */
    public function testReadAfterCloseReturnsEmptyString(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');
        $stream->close();

        TestCase::assertSame('', $stream->read(100));
    }
}
