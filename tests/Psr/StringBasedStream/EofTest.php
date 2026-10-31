<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class EofTest extends TestCase
{
    /**
     * Tests receiving bool when using eof() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::eof
     */
    public function testEofWithEmptyStringReturnsTrue(): void
    {
        $stream = StringBasedStream::createFromString('');

        self::assertTrue($stream->eof());
    }

    /**
     * Tests receiving bool when using eof() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::eof
     */
    public function testEofWithStringReturnsFalse(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        self::assertFalse($stream->eof());
    }

    /**
     * Tests receiving bool when using eof() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::eof
     */
    public function testEofAfterSeekReturnsTrue(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');
        $stream->seek(0, SEEK_END);

        self::assertTrue($stream->eof());
    }

    /**
     * Tests receiving bool when using eof() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::eof
     */
    public function testEofAfterCloseReturnsTrue(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');
        $stream->close();

        self::assertTrue($stream->eof());
    }
}
