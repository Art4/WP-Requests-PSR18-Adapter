<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class StreamTest extends TestCase
{
    /**
     * Tests all properties are set when using createFromString().
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::createFromString
     *
     * @return void
     */
    public function testCreateFromStringReturnsStreamWithAllProperties()
    {
        $stream = StringBasedStream::createFromString('foobar');

        $this->assertSame(6, $stream->getSize());
        $this->assertSame('foobar', $stream->__toString());
    }
}
