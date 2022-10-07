<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use RuntimeException;
use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class ReadTest extends TestCase
{
    /**
     * Tests receiving an exception when using read() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::read
     *
     * @return void
     */
    public function testReadThrowsRuntimeException()
    {
        $stream = StringBasedStream::createFromString('');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('%s::read() is not implemented.', StringBasedStream::class));

        $stream->read(0);
    }
}
