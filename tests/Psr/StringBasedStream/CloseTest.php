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
     *
     * @return void
     */
    public function testCloseReturnsVoid()
    {
        $this->expectNotToPerformAssertions();

        $stream = StringBasedStream::createFromString('');

        $stream->close();
    }
}
