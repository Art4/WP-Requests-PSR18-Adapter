<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class CloseTest extends TestCase
{
    /**
     * Tests receiving void when using close() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::close
     */
    public function testCloseReturnsVoid(): void
    {
        $this->expectNotToPerformAssertions();

        $stream = StringBasedStream::createFromString('');

        $stream->close();
    }
}
