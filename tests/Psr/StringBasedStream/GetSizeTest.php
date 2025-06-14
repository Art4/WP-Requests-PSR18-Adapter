<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetSizeTest extends TestCase
{
    /**
     * Tests returns content size when using getSize() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::getSize
     */
    public function testGetSizeReturnsContentSize(): void
    {
        $stream = StringBasedStream::createFromString('full content');

        TestCase::assertSame(12, $stream->getSize());
    }
}
