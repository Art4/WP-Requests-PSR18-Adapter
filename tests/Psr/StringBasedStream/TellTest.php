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
    public function testTellReturnsZeroOnFirstCall(): void
    {
        $stream = StringBasedStream::createFromString('0123456789');

        TestCase::assertSame(0, $stream->tell());
    }
}
