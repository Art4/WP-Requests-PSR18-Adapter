<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class IsSeekableTest extends TestCase
{
    /**
     * Tests receiving false when using isSeekable() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::isSeekable
     */
    public function testIsSeekableReturnsFalse(): void
    {
        $stream = StringBasedStream::createFromString('');

        TestCase::assertFalse($stream->isSeekable());
    }
}
