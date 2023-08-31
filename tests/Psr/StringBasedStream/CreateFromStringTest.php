<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Psr\Http\Message\StreamInterface;
use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class CreateFromStringTest extends TestCase
{
    /**
     * Tests receiving the stream when using createFromString().
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::createFromString
     * @covers \Art4\Requests\Psr\StringBasedStream::__construct
     *
     * @return void
     */
    public function testCreateFromStringReturnsStream()
    {
        $this->assertInstanceOf(
            StreamInterface::class,
            StringBasedStream::createFromString('')
        );
    }
}
