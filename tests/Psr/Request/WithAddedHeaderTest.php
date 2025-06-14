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

final class WithAddedHeaderTest extends TestCase
{
    /**
     * Tests changing the header when using withAddedHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withAddedHeader
     */
    public function testWithAddedHeaderReturnsRequest(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        TestCase::assertInstanceOf(RequestInterface::class, $request->withAddedHeader('name', 'value'));
    }

    /**
     * Tests changing the header when using withAddedHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withAddedHeader
     */
    public function testWithAddedHeaderReturnsNewInstance(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        TestCase::assertNotSame($request, $request->withAddedHeader('name', 'value'));
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
     * Tests receiving an exception when the withAddedHeader() method received an invalid input type as `$value`.
     *
     * @dataProvider dataInvalidTypeNotStringOrArray
     *
     * @covers \Art4\Requests\Psr\Request::withAddedHeader
     *
     * @param mixed $input Invalid parameter input.
     */
    #[DataProvider('dataInvalidTypeNotStringOrArray')]
    public function testWithAddedHeaderWithoutValueAsStringOrArrayThrowsInvalidArgumentException($input): void
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
    public static function dataInvalidTypeNotStringOrArray(): array
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
     */
    #[DataProvider('dataInvalidTypeNotString')]
    public function testWithAddedHeaderWithoutValueAsStringInArrayThrowsInvalidArgumentException($input): void
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
     */
    public function testWithAddedHeaderChangesTheHeaders(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');
        $request = Request::withMethodAndUri('GET', $uri);

        $request = $request->withAddedHeader('Name', 'value');

        TestCase::assertSame(['Name' => ['value']], $request->getHeaders());
    }

    /**
     * Tests changing the header when using withAddedHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withAddedHeader
     * @covers \Art4\Requests\Psr\Request::updateHeader
     */
    public function testWithAddedHeaderCaseInsensitiveChangesTheHeaders(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');
        $request = Request::withMethodAndUri('GET', $uri);

        $request = $request->withAddedHeader('name', 'value1');
        $request = $request->withAddedHeader('NAME', 'value2');

        TestCase::assertSame(['NAME' => ['value1', 'value2']], $request->getHeaders());
    }
}
