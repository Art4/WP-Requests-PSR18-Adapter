<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use Art4\Requests\Psr\Request;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class GetHeaderTest extends TestCase
{
    /**
     * Tests receiving the header when using getHeader().
     *
     * @covers \Art4\Requests\Psr\Request::getHeader
     *
     * @return void
     */
    public function testGetHeaderWithoutHeaderReturnsEmptyArray()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertSame([], $request->getHeader('name'));
    }

    /**
     * Tests receiving the header when using getHeader().
     *
     * @covers \Art4\Requests\Psr\Request::getHeader
     *
     * @return void
     */
    public function testGetHeaderReturnsArray()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));
        $request = $request->withHeader('name', 'value');

        $this->assertSame(['value'], $request->getHeader('name'));
    }

    /**
     * Tests receiving the header when using getHeader().
     *
     * @covers \Art4\Requests\Psr\Request::getHeader
     *
     * @return void
     */
    public function testGetHeaderWithCaseInsensitiveNameReturnsArray()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));
        $request = $request->withHeader('name', 'value');

        $this->assertSame(['value'], $request->getHeader('NAME'));
    }

    /**
     * Tests receiving an exception when the getHeader() method received an invalid input type as `$name`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Request::getHeader
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    public function testGetHeaderWithoutStringThrowsInvalidArgumentException($input)
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::getHeader(): Argument #1 ($name) must be of type string,', Request::class));

        $request->getHeader($input);
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
