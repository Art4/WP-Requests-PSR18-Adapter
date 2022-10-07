<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use RuntimeException;
use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class RewindTest extends TestCase
{
    /**
     * Tests receiving an exception when using rewind() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::rewind
     *
     * @return void
     */
    public function testRewindThrowsRuntimeException()
    {
        $stream = StringBasedStream::createFromString('');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('%s::rewind() is not implemented.', StringBasedStream::class));

        $stream->rewind();
    }
}
