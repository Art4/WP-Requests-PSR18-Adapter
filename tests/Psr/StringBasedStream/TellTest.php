<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class TellTest extends TestCase
{
    /**
     * Tests receiving an integer when using tell() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::tell
     */
    public function testTellOnFirstCallReturnsZero(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        TestCase::assertSame(0, $stream->tell());
    }

    /**
     * Tests receiving an integer when using tell() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::tell
     */
    public function testTellAfterSeekWithSeekSetReturnsInteger(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $stream->seek(3, SEEK_SET);

        TestCase::assertSame(3, $stream->tell());
    }

    /**
     * Tests receiving an integer when using tell() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::tell
     */
    public function testTellAfterSeekWithSeekCurReturnsInteger(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $stream->seek(5, SEEK_SET);
        $stream->seek(3, SEEK_CUR);

        TestCase::assertSame(8, $stream->tell());
    }

    /**
     * Tests receiving an integer when using tell() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::tell
     */
    public function testTellAfterSeekWithSeekEndReturnsInteger(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $stream->seek(5, SEEK_SET);
        $stream->seek(0, SEEK_END);

        TestCase::assertSame(10, $stream->tell());
    }
}
