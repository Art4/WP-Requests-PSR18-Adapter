<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use RuntimeException;
use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetContentsTest extends TestCase
{
    /**
     * Tests receiving an exception when using getContents() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::getContents
     *
     * @return void
     */
    public function testGetContentsThrowsRuntimeException()
    {
        $stream = StringBasedStream::createFromString('');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('%s::getContents() is not implemented.', StringBasedStream::class));

        $stream->getContents();
    }
}
