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
     *
     * @return void
     */
    public function testGetMetadataReturnsArray()
    {
        $stream = StringBasedStream::createFromString('');

        $this->assertSame([], $stream->getMetadata());
    }

    /**
     * Tests receiving null when using getMetadata().
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::getMetadata
     *
     * @return void
     */
    public function testGetMetadataWithKeyReturnsNull()
    {
        $stream = StringBasedStream::createFromString('');

        $this->assertNull($stream->getMetadata('key'));
    }
}
