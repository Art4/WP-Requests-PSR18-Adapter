<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Psr\Http\Message\UriInterface;
use Art4\Requests\Psr\Request;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetHeadersTest extends TestCase
{
    /**
     * Tests receiving the headers when using getHeaders().
     *
     * @covers \Art4\Requests\Psr\Request::getHeaders
     *
     * @return void
     */
    public function testGetHeadersReturnsArray()
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('');
        $request = Request::withMethodAndUri('GET', $uri);

        $this->assertSame([], $request->getHeaders());
    }

    /**
     * Tests receiving the headers when using getHeaders().
     *
     * @covers \Art4\Requests\Psr\Request::getHeaders
     *
     * @return void
     */
    public function testGetHeadersReturnsArrayWithHostHeader()
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('example.org');
        $request = Request::withMethodAndUri('GET', $uri);

        $this->assertSame(['Host' => ['example.org']], $request->getHeaders());
    }
}
