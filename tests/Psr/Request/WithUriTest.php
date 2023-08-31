<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Art4\Requests\Psr\Request;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithUriTest extends TestCase
{
    /**
     * Tests changing the uri when using withUri().
     *
     * @covers \Art4\Requests\Psr\Request::withUri
     *
     * @return void
     */
    public function testWithUriReturnsRequest()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');

        $this->assertInstanceOf(RequestInterface::class, $request->withUri($uri));
    }

    /**
     * Tests changing the uri when using withUri().
     *
     * @covers \Art4\Requests\Psr\Request::withUri
     *
     * @return void
     */
    public function testWithUriReturnsNewInstance()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');

        $this->assertNotSame($request, $request->withUri($uri));
    }

    /**
     * Tests changing the uri when using withUri().
     *
     * @covers \Art4\Requests\Psr\Request::withUri
     * @covers \Art4\Requests\Psr\Request::setUri
     * @covers \Art4\Requests\Psr\Request::updateHeader
     *
     * @return void
     */
    public function testWithUriChangesTheUri()
    {
        $uri1 = $this->createMock(UriInterface::class);
        $request = Request::withMethodAndUri('GET', $uri1);

        $uri2 = $this->createMock(UriInterface::class);
        $uri2->method('getHost')->willReturn('');
        $request = $request->withUri($uri2);

        $this->assertSame($uri2, $request->getUri());
    }

    /**
     * Tests changing the uri when using withUri().
     *
     * @covers \Art4\Requests\Psr\Request::withUri
     * @covers \Art4\Requests\Psr\Request::setUri
     * @covers \Art4\Requests\Psr\Request::updateHeader
     *
     * @return void
     */
    public function testWithUriChangesTheHostHeader()
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('example.org');
        $request = Request::withMethodAndUri('GET', $uri);

        $this->assertSame(['Host' => ['example.org']], $request->getHeaders());

        $uri2 = $this->createMock(UriInterface::class);
        $uri2->method('getHost')->willReturn('example.com');
        $request = $request->withUri($uri2);

        $this->assertSame(['Host' => ['example.com']], $request->getHeaders());
    }

    /**
     * Tests changing the uri when using withUri().
     *
     * @covers \Art4\Requests\Psr\Request::withUri
     * @covers \Art4\Requests\Psr\Request::setUri
     * @covers \Art4\Requests\Psr\Request::updateHeader
     *
     * @return void
     */
    public function testWithUriChangesTheHostHeaderToFirstPlace()
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');
        $request = Request::withMethodAndUri('GET', $uri);
        $request = $request->withHeader('name', 'value');

        $this->assertSame(['name' => ['value']], $request->getHeaders());

        $uri2 = $this->createMock(UriInterface::class);
        $uri2->method('getHost')->willReturn('example.com');
        $request = $request->withUri($uri2);

        $this->assertSame(['Host' => ['example.com'], 'name' => ['value']], $request->getHeaders());
    }

    /**
     * Tests changing the uri when using withUri().
     *
     * @covers \Art4\Requests\Psr\Request::withUri
     * @covers \Art4\Requests\Psr\Request::setUri
     * @covers \Art4\Requests\Psr\Request::updateHeader
     *
     * @return void
     */
    public function testWithUriWithoutHostDoNotChangeTheHostHeader()
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('example.org');
        $request = Request::withMethodAndUri('GET', $uri);

        $this->assertSame(['Host' => ['example.org']], $request->getHeaders());

        $uri2 = $this->createMock(UriInterface::class);
        $uri2->method('getHost')->willReturn('');
        $request = $request->withUri($uri2);

        $this->assertSame(['Host' => ['example.org']], $request->getHeaders());
    }

    /**
     * Tests changing the uri when using withUri().
     *
     * @dataProvider dataPreserveHost
     *
     * @covers \Art4\Requests\Psr\Request::withUri
     * @covers \Art4\Requests\Psr\Request::setUri
     * @covers \Art4\Requests\Psr\Request::updateHeader
     *
     * @param array<string,string[]> $expectedHeaders
     *
     * @return void
     */
    public function testWithUriAndPreserveHost(string $initHost, string $newHost, array $expectedHeaders)
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn($initHost);
        $request = Request::withMethodAndUri('GET', $uri);

        $uri2 = $this->createMock(UriInterface::class);
        $uri2->method('getHost')->willReturn($newHost);
        $request = $request->withUri($uri2, true);

        $this->assertSame($expectedHeaders, $request->getHeaders());
    }

    /**
     * Data Provider.
     *
     * @return array<string,array<string|array<string,string[]>>>
     */
    public static function dataPreserveHost(): array
    {
        return [
            // 'Host header is missing or empty, and the new URI contains a host component, this method MUST update the Host header' => ['', 'example.org', ['Host' => ['example.org']]],
            // 'Host header is missing or empty, and the new URI does not contain a host component, this method MUST NOT update the Host header' => ['', '', []],
            'If a Host header is present and non-empty, this method MUST NOT update the Host header' => ['example.org', 'example.com', ['Host' => ['example.org']]],
        ];
    }
}
