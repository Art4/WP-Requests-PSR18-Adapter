<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Art4\Requests\Psr\Request;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class WithAddedHeaderTest extends TestCase
{
    /**
     * Tests changing the header when using withAddedHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withAddedHeader
     *
     * @return void
     */
    public function testWithAddedHeaderReturnsRequest()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertInstanceOf(RequestInterface::class, $request->withAddedHeader('name', 'value'));
    }

    /**
     * Tests changing the header when using withAddedHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withAddedHeader
     *
     * @return void
     */
    public function testWithAddedHeaderReturnsNewInstance()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertNotSame($request, $request->withAddedHeader('name', 'value'));
    }

    /**
     * Data Provider.
     *
     * @return array<string, mixed>
     */
    public static function dataInvalidTypeNotString()
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_STRING);
    }

    /**
     * Tests receiving an exception when the withAddedHeader() method received an invalid input type as `$value`.
     *
     * @dataProvider dataInvalidTypeNotStringOrArray
     *
     * @covers \Art4\Requests\Psr\Request::withAddedHeader
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    public function testWithAddedHeaderWithoutValueAsStringOrArrayThrowsInvalidArgumentException($input)
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withAddedHeader(): Argument #2 ($value) must be of type string|array', Request::class));

        $request->withAddedHeader('name', $input);
    }

    /**
     * Data Provider.
     *
     * @return array<string, mixed>
     */
    public static function dataInvalidTypeNotStringOrArray()
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_STRING, TypeProviderHelper::GROUP_ARRAY);
    }

    /**
     * Tests receiving an exception when the withAddedHeader() method received an invalid input type as `$value`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Request::withAddedHeader
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    public function testWithAddedHeaderWithoutValueAsStringInArrayThrowsInvalidArgumentException($input)
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withAddedHeader(): Argument #2 ($value) must be of type string|array containing strings', Request::class));

        $request->withAddedHeader('name', [$input]);
    }

    /**
     * Tests changing the header when using withAddedHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withAddedHeader
     * @covers \Art4\Requests\Psr\Request::updateHeader
     *
     * @return void
     */
    public function testWithAddedHeaderChangesTheHeaders()
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');
        $request = Request::withMethodAndUri('GET', $uri);

        $request = $request->withAddedHeader('Name', 'value');

        $this->assertSame(['Name' => ['value']], $request->getHeaders());
    }

    /**
     * Tests changing the header when using withAddedHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withAddedHeader
     * @covers \Art4\Requests\Psr\Request::updateHeader
     *
     * @return void
     */
    public function testWithAddedHeaderCaseInsensitiveChangesTheHeaders()
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');
        $request = Request::withMethodAndUri('GET', $uri);

        $request = $request->withAddedHeader('name', 'value1');
        $request = $request->withAddedHeader('NAME', 'value2');

        $this->assertSame(['NAME' => ['value1', 'value2']], $request->getHeaders());
    }
}
