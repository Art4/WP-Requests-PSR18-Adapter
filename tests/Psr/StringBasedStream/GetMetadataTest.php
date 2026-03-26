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

        TestCase::assertSame(
            [
                'seekable' => true,
                'readable' => true,
                'writable' => false,
            ],
            $stream->getMetadata()
        );
    }

    /**
     * Tests receiving null when using getMetadata().
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::getMetadata
     */
    public function testGetMetadataWithKeysReturnsValues(): void
    {
        $stream = StringBasedStream::createFromString('');

        TestCase::assertTrue($stream->getMetadata('seekable'));
        TestCase::assertTrue($stream->getMetadata('readable'));
        TestCase::assertFalse($stream->getMetadata('writable'));
    }

    /**
     * Tests receiving null when using getMetadata().
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::getMetadata
     */
    public function testGetMetadataWithSeekableKeyAfterCloseReturnsFalse(): void
    {
        $stream = StringBasedStream::createFromString('');
        $stream->close();

        TestCase::assertFalse($stream->getMetadata('seekable'));
    }

    /**
     * Tests receiving null when using getMetadata().
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::getMetadata
     */
    public function testGetMetadataWithUnknownKeyReturnsNull(): void
    {
        $stream = StringBasedStream::createFromString('');

        TestCase::assertNull($stream->getMetadata('key'));
    }
}
