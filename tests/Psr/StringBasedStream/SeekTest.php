<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use RuntimeException;
use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class SeekTest extends TestCase
{
    /**
     * Tests receiving an exception when using seek() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::seek
     *
     * @return void
     */
    public function testSeekThrowsRuntimeException()
    {
        $stream = StringBasedStream::createFromString('');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('%s::seek() is not implemented.', StringBasedStream::class));

        $stream->seek(0);
    }
}
