<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Psr\Http\Message\StreamInterface;
use WpOrg\Requests\Exception\InvalidArgument;
use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class CreateFromStringTest extends TestCase
{
    /**
     * Tests receiving the stream when using createFromString().
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::createFromString
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

    /**
     * Tests receiving an exception when the createFromString() method received an invalid input type as `$method`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::createFromString
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    public function testCreateFromStringWithoutStringThrowsException($input)
    {
        $this->expectException(InvalidArgument::class);
        $this->expectExceptionMessage(sprintf('%s::createFromString(): Argument #1 ($content) must be of type string, ', StringBasedStream::class));

        StringBasedStream::createFromString($input);
    }

    /**
     * Data Provider.
     *
     * @return array
     */
    public function dataInvalidTypeNotString()
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_STRING);
    }
}
