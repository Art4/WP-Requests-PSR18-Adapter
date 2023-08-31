<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class __ToStringTest extends TestCase
{
    /**
     * Tests returns the full content when using __toString() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::__toString
     *
     * @return void
     */
    public function testToStringReturnsFullContent()
    {
        $stream = StringBasedStream::createFromString('full content');

        $this->assertSame('full content', $stream->__toString());
    }
}
