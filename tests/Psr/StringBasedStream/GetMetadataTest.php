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

        self::assertSame(
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

        self::assertTrue($stream->getMetadata('seekable'));
        self::assertTrue($stream->getMetadata('readable'));
        self::assertFalse($stream->getMetadata('writable'));
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

        self::assertFalse($stream->getMetadata('seekable'));
    }

    /**
     * Tests receiving null when using getMetadata().
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::getMetadata
     */
    public function testGetMetadataWithUnknownKeyReturnsNull(): void
    {
        $stream = StringBasedStream::createFromString('');

        self::assertNull($stream->getMetadata('key'));
    }
}
