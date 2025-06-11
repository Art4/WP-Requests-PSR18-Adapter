<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Art4\Requests\Psr\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithBodyTest extends TestCase
{
    /**
     * Tests changing the body when using withBody().
     *
     * @covers \Art4\Requests\Psr\Request::withBody
     */
    public function testWithBodyReturnsRequest(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $body = $this->createMock(StreamInterface::class);

        TestCase::assertInstanceOf(RequestInterface::class, $request->withBody($body));
    }

    /**
     * Tests changing the body when using withBody().
     *
     * @covers \Art4\Requests\Psr\Request::withBody
     */
    public function testWithBodyReturnsNewInstance(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $body = $this->createMock(StreamInterface::class);

        TestCase::assertNotSame($request, $request->withBody($body));
    }
}
