<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetMetadataTest extends TestCase
{
    /**
     * Tests receiving an array when using getMetadata().
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::getMetadata
     */
    public function testGetMetadataReturnsArray(): void
    {
        $stream = StringBasedStream::createFromString('');

        TestCase::assertSame([], $stream->getMetadata());
    }

    /**
     * Tests receiving null when using getMetadata().
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::getMetadata
     */
    public function testGetMetadataWithKeyReturnsNull(): void
    {
        $stream = StringBasedStream::createFromString('');

        TestCase::assertNull($stream->getMetadata('key'));
    }
}
