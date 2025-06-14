<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use RuntimeException;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class ReadTest extends TestCase
{
    /**
     * Tests receiving an exception when using read() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::read
     */
    public function testReadThrowsRuntimeException(): void
    {
        $stream = StringBasedStream::createFromString('');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('%s::read() is not implemented.', StringBasedStream::class));

        $stream->read(0);
    }
}
