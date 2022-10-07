<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use RuntimeException;
use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class TellTest extends TestCase
{
    /**
     * Tests receiving an exception when using tell() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::tell
     *
     * @return void
     */
    public function testTellThrowsRuntimeException()
    {
        $stream = StringBasedStream::createFromString('');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('%s::tell() is not implemented.', StringBasedStream::class));

        $stream->tell();
    }
}
