<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use RuntimeException;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class SeekTest extends TestCase
{
    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekWithoutWhenceParameter(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $stream->seek(3);

        TestCase::assertSame(3, $stream->tell());
    }

    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekWithInvalidWhenceParameterThrowsException(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid whence.');

        $stream->seek(3, 404);
    }

    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekWithSeekSet(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $stream->seek(3, SEEK_SET);

        TestCase::assertSame(3, $stream->tell());
    }

    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekWithSeekSetThrowsException(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot seek to position 11');

        $stream->seek(11, SEEK_SET);
    }

    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekWithNegativeIntAndSeekSetThrowsException(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot seek to position -11');

        $stream->seek(-11, SEEK_SET);
    }

    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekWithSeekCur(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $stream->seek(3, SEEK_CUR);

        TestCase::assertSame(3, $stream->tell());
    }

    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekWithNegativeIntegerAndSeekCur(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $stream->seek(10, SEEK_CUR);
        $stream->seek(-3, SEEK_CUR);

        TestCase::assertSame(7, $stream->tell());
    }

    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekWithSeekCurThrowsException(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot seek to position 11');

        $stream->seek(11, SEEK_CUR);
    }

    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekWithNegativeIntegerAndSeekCurThrowsException(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot seek to position -11');

        $stream->seek(-11, SEEK_CUR);
    }

    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekWithSeekSetAndSeekCurThrowsException(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $stream->seek(6, SEEK_SET);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot seek to position 12');

        $stream->seek(6, SEEK_CUR);
    }

    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekWithSeekSetAndNegativeIntegerAndSeekCurThrowsException(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $stream->seek(6, SEEK_SET);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot seek to position -1');

        $stream->seek(-7, SEEK_CUR);
    }

    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekWithSeekEnd(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $stream->seek(0, SEEK_END);

        TestCase::assertSame(10, $stream->tell());
    }

    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekWithNegativeIntegerAndSeekEnd(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $stream->seek(-3, SEEK_END);

        TestCase::assertSame(7, $stream->tell());
    }

    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekWithSeekEndThrowsException(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot seek to position 15');

        $stream->seek(5, SEEK_END);
    }

    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekWithNegativeIntegerAndSeekEndThrowsException(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot seek to position -5');

        $stream->seek(-15, SEEK_END);
    }

    /**
     * Tests using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     */
    public function testSeekAfterCloseThrowsException(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');
        $stream->close();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Stream is closed.');

        $stream->seek(0);
    }
}
