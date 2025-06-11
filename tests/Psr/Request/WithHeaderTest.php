<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Art4\Requests\Psr\Request;
use Art4\Requests\Tests\TypeProviderHelper;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithHeaderTest extends TestCase
{
    /**
     * Tests changing the header when using withHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withHeader
     * @covers \Art4\Requests\Psr\Request::updateHeader
     */
    public function testWithHeaderReturnsRequest(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        TestCase::assertInstanceOf(RequestInterface::class, $request->withHeader('name', 'value'));
    }

    /**
     * Tests changing the header when using withHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withHeader
     */
    public function testWithHeaderReturnsNewInstance(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        TestCase::assertNotSame($request, $request->withHeader('name', 'value'));
    }

    /**
     * Data Provider.
     *
     * @return array<string, mixed>
     */
    public static function dataInvalidTypeNotString(): array
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
     */
    #[DataProvider('dataInvalidTypeNotStringOrArray')]
    public function testWithHeaderWithoutValueAsStringOrArrayThrowsInvalidArgumentException($input): void
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
    public static function dataInvalidTypeNotStringOrArray(): array
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
     */
    #[DataProvider('dataInvalidTypeNotString')]
    public function testWithHeaderWithoutValueAsStringInArrayThrowsInvalidArgumentException($input): void
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
     */
    public function testWithHeaderChangesTheHeaders(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');
        $request = Request::withMethodAndUri('GET', $uri);

        $request = $request->withHeader('Name', 'value');

        TestCase::assertSame(['Name' => ['value']], $request->getHeaders());
    }

    /**
     * Tests changing the header when using withHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withHeader
     * @covers \Art4\Requests\Psr\Request::updateHeader
     */
    public function testWithHeaderCaseInsensitiveChangesTheHeaders(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');
        $request = Request::withMethodAndUri('GET', $uri);

        $request = $request->withHeader('name', 'value');
        $request = $request->withHeader('NAME', 'value');

        TestCase::assertSame(['NAME' => ['value']], $request->getHeaders());
    }
}
