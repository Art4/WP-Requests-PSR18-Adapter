<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Art4\Requests\Psr\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class WithMethodAndUriTest extends TestCase
{
    /**
     * Tests receiving a Request instance when using withMethodAndUri().
     *
     * @covers \Art4\Requests\Psr\Request::withMethodAndUri
     * @covers \Art4\Requests\Psr\Request::__construct
     *
     * @return void
     */
    public function testWithMethodAndUriReturnsRequest()
    {
        $uri = $this->createMock(UriInterface::class);

        $this->assertInstanceOf(
            RequestInterface::class,
            Request::withMethodAndUri('', $uri)
        );
    }

    /**
     * Tests receiving a Request instance when using withMethodAndUri().
     *
     * @covers \Art4\Requests\Psr\Request::withUri
     *
     * @return void
     */
    public function testWithMethodAndUriChangesTheHostHeader()
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getHost')->willReturn('example.org');
        $request = Request::withMethodAndUri('GET', $uri);

        $this->assertSame(['Host' => ['example.org']], $request->getHeaders());
    }
}
