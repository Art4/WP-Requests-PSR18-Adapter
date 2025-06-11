<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Art4\Requests\Psr\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithoutHeaderTest extends TestCase
{
    /**
     * Tests removing the header when using withoutHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withoutHeader
     */
    public function testWithoutHeaderReturnsRequest(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        TestCase::assertInstanceOf(RequestInterface::class, $request->withoutHeader('name'));
    }

    /**
     * Tests removing the header when using withoutHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withoutHeader
     */
    public function testWithoutHeaderReturnsNewInstance(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        TestCase::assertNotSame($request, $request->withoutHeader('name'));
    }

    /**
     * Tests removing the header when using withoutHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withoutHeader
     * @covers \Art4\Requests\Psr\Request::updateHeader
     */
    public function testWithoutHeaderChangesTheHeaders(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');
        $request = Request::withMethodAndUri('GET', $uri);

        $request = $request->withHeader('Name', 'value');
        $request = $request->withoutHeader('Name');

        TestCase::assertSame([], $request->getHeaders());
    }

    /**
     * Tests removing the header when using withoutHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withoutHeader
     * @covers \Art4\Requests\Psr\Request::updateHeader
     */
    public function testWithoutHeaderCaseInsensitiveChangesTheHeaders(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');
        $request = Request::withMethodAndUri('GET', $uri);

        $request = $request->withHeader('NAME1', 'value1');
        $request = $request->withHeader('NAME2', 'value2');
        $request = $request->withoutHeader('name1');

        TestCase::assertSame(['NAME2' => ['value2']], $request->getHeaders());
    }
}
