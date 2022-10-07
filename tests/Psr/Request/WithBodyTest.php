<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Art4\Requests\Psr\Request;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithBodyTest extends TestCase
{
    /**
     * Tests changing the body when using withBody().
     *
     * @covers \Art4\Requests\Psr\Request::withBody
     *
     * @return void
     */
    public function testWithBodyReturnsRequest()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $body = $this->createMock(StreamInterface::class);

        $this->assertInstanceOf(RequestInterface::class, $request->withBody($body));
    }

    /**
     * Tests changing the body when using withBody().
     *
     * @covers \Art4\Requests\Psr\Request::withBody
     *
     * @return void
     */
    public function testWithBodyReturnsNewInstance()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $body = $this->createMock(StreamInterface::class);

        $this->assertNotSame($request, $request->withBody($body));
    }
}
