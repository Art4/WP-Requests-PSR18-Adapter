<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use RuntimeException;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetContentsTest extends TestCase
{
    /**
     * Tests receiving a string when using getContents() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::getContents
     */
    public function testGetContentsReturnsEmptyString(): void
    {
        $content = '';

        $stream = StringBasedStream::createFromString($content);

        TestCase::assertSame($content, $stream->getContents());
    }

    /**
     * Tests receiving a string when using getContents() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::getContents
     */
    public function testGetContentsReturnsString(): void
    {
        $content = '{"data":"some json"}';

        $stream = StringBasedStream::createFromString($content);

        TestCase::assertSame($content, $stream->getContents());
    }

    /**
     * Tests receiving a string when using getContents() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::getContents
     */
    public function testGetContentsRespectsPointer(): void
    {
        $content = '0123456789';

        $stream = StringBasedStream::createFromString($content);
        $stream->seek(5, SEEK_SET);

        TestCase::assertSame('56789', $stream->getContents());
    }
}
