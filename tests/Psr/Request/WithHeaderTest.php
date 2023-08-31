<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Art4\Requests\Psr\Request;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class WithHeaderTest extends TestCase
{
    /**
     * Tests changing the header when using withHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withHeader
     * @covers \Art4\Requests\Psr\Request::updateHeader
     *
     * @return void
     */
    public function testWithHeaderReturnsRequest()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertInstanceOf(RequestInterface::class, $request->withHeader('name', 'value'));
    }

    /**
     * Tests changing the header when using withHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withHeader
     *
     * @return void
     */
    public function testWithHeaderReturnsNewInstance()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertNotSame($request, $request->withHeader('name', 'value'));
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
     * Tests receiving an exception when the withHeader() method received an invalid input type as `$value`.
     *
     * @dataProvider dataInvalidTypeNotStringOrArray
     *
     * @covers \Art4\Requests\Psr\Request::withHeader
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    public function testWithHeaderWithoutValueAsStringOrArrayThrowsInvalidArgumentException($input)
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withHeader(): Argument #2 ($value) must be of type string|array', Request::class));

        $request->withHeader('name', $input);
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
    public function testWithHeaderWithoutValueAsStringInArrayThrowsInvalidArgumentException($input)
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withHeader(): Argument #2 ($value) must be of type string|array containing strings', Request::class));

        $request->withHeader('name', [$input]);
    }

    /**
     * Tests changing the header when using withHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withHeader
     * @covers \Art4\Requests\Psr\Request::updateHeader
     *
     * @return void
     */
    public function testWithHeaderChangesTheHeaders()
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');
        $request = Request::withMethodAndUri('GET', $uri);

        $request = $request->withHeader('Name', 'value');

        $this->assertSame(['Name' => ['value']], $request->getHeaders());
    }

    /**
     * Tests changing the header when using withHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withHeader
     * @covers \Art4\Requests\Psr\Request::updateHeader
     *
     * @return void
     */
    public function testWithHeaderCaseInsensitiveChangesTheHeaders()
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');
        $request = Request::withMethodAndUri('GET', $uri);

        $request = $request->withHeader('name', 'value');
        $request = $request->withHeader('NAME', 'value');

        $this->assertSame(['NAME' => ['value']], $request->getHeaders());
    }
}
