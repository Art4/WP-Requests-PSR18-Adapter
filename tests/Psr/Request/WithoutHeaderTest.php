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
     *
     * @return void
     */
    public function testWithoutHeaderReturnsRequest()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertInstanceOf(RequestInterface::class, $request->withoutHeader('name'));
    }

    /**
     * Tests removing the header when using withoutHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withoutHeader
     *
     * @return void
     */
    public function testWithoutHeaderReturnsNewInstance()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertNotSame($request, $request->withoutHeader('name'));
    }

    /**
     * Tests removing the header when using withoutHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withoutHeader
     * @covers \Art4\Requests\Psr\Request::updateHeader
     *
     * @return void
     */
    public function testWithoutHeaderChangesTheHeaders()
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');
        $request = Request::withMethodAndUri('GET', $uri);

        $request = $request->withHeader('Name', 'value');
        $request = $request->withoutHeader('Name');

        $this->assertSame([], $request->getHeaders());
    }

    /**
     * Tests removing the header when using withoutHeader().
     *
     * @covers \Art4\Requests\Psr\Request::withoutHeader
     * @covers \Art4\Requests\Psr\Request::updateHeader
     *
     * @return void
     */
    public function testWithoutHeaderCaseInsensitiveChangesTheHeaders()
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');
        $request = Request::withMethodAndUri('GET', $uri);

        $request = $request->withHeader('NAME1', 'value1');
        $request = $request->withHeader('NAME2', 'value2');
        $request = $request->withoutHeader('name1');

        $this->assertSame(['NAME2' => ['value2']], $request->getHeaders());
    }
}
