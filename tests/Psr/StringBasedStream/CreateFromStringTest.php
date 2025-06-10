<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use Art4\Requests\Tests\TypeProviderHelper;
use Psr\Http\Message\StreamInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class CreateFromStringTest extends TestCase
{
    /**
     * Tests receiving the stream when using createFromString().
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::createFromString
     * @covers \Art4\Requests\Psr\StringBasedStream::__construct
     */
    public function testCreateFromStringReturnsStream(): void
    {
        TestCase::assertInstanceOf(
            StreamInterface::class,
            StringBasedStream::createFromString('')
        );
    }
}
