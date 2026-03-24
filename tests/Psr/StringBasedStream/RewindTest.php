<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use RuntimeException;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class RewindTest extends TestCase
{
    /**
     * Tests using rewind() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::rewind
     */
    public function testRewindCallsSeek(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        $stream->seek(5, SEEK_SET);

        $stream->rewind();

        TestCase::assertSame(0, $stream->tell());
    }
}
