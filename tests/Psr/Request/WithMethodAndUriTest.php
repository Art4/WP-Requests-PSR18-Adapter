<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Art4\Requests\Psr\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithMethodAndUriTest extends TestCase
{
    /**
     * Tests receiving a Request instance when using withMethodAndUri().
     *
     * @covers \Art4\Requests\Psr\Request::withMethodAndUri
     * @covers \Art4\Requests\Psr\Request::__construct
     */
    public function testWithMethodAndUriReturnsRequest(): void
    {
        $uri = $this->createMock(UriInterface::class);

        TestCase::assertInstanceOf(
            RequestInterface::class,
            Request::withMethodAndUri('GET', $uri)
        );
    }

    /**
     * Tests throwing an exception when using withMethodAndUri() with empty method.
     *
     * @covers \Art4\Requests\Psr\Request::withMethodAndUri
     * @covers \Art4\Requests\Psr\Request::__construct
     */
    public function testWithMethodAndUriWithEmptyMethodThrowsException(): void
    {
        $uri = $this->createMock(UriInterface::class);

        TestCase::expectException(\InvalidArgumentException::class);
        TestCase::expectExceptionMessage('Method must be a non-empty string');

        Request::withMethodAndUri('', $uri);
    }

    /**
     * Tests receiving a Request instance when using withMethodAndUri().
     *
     * @covers \Art4\Requests\Psr\Request::withUri
     */
    public function testWithMethodAndUriChangesTheHostHeader(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('example.org');
        $request = Request::withMethodAndUri('GET', $uri);

        TestCase::assertSame(['Host' => ['example.org']], $request->getHeaders());
    }
}
