<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use InvalidArgumentException;
use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

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

    /**
     * Tests receiving an exception when the withHeader() method received an invalid input type as `$value`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Request::withHeader
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    public function testGetMetadataWithoutStringThrowsInvalidArgumentException($input)
    {
        $stream = StringBasedStream::createFromString('');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::getMetadata(): Argument #1 ($key) must be of type string', StringBasedStream::class));

        $stream->getMetadata($input);
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
