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

        TestCase::assertTrue($stream->eof());
    }

    /**
     * Tests receiving bool when using eof() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::eof
     */
    public function testEofWithStringReturnsFalse(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        TestCase::assertFalse($stream->eof());
    }

    /**
     * Tests receiving bool when using eof() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::eof
     */
    public function testEofAfterTellReturnsTrue(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');
        $stream->seek(0, SEEK_END);

        TestCase::assertTrue($stream->eof());
    }
}
