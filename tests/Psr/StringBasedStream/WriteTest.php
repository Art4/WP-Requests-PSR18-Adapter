<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use RuntimeException;
use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WriteTest extends TestCase
{
    /**
     * Tests receiving an exception when using write() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::write
     *
     * @return void
     */
    public function testWriteThrowsRuntimeException()
    {
        $stream = StringBasedStream::createFromString('');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('%s::write() is not implemented.', StringBasedStream::class));

        $stream->write('');
    }
}
