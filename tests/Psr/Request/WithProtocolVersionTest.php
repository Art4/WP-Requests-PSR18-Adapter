<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Art4\Requests\Psr\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithProtocolVersionTest extends TestCase
{
    /**
     * Tests changing the version when using withProtocolVersion().
     *
     * @covers \Art4\Requests\Psr\Request::withProtocolVersion
     *
     * @return void
     */
    public function testWithProtocolVersionReturnsRequest()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertInstanceOf(RequestInterface::class, $request->withProtocolVersion('1.0'));
    }

    /**
     * Tests changing the version when using withProtocolVersion().
     *
     * @covers \Art4\Requests\Psr\Request::withProtocolVersion
     *
     * @return void
     */
    public function testWithProtocolVersionReturnsNewInstance()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertNotSame($request, $request->withProtocolVersion('1.0'));
    }

    /**
     * Tests changing the version when using withProtocolVersion().
     *
     * @covers \Art4\Requests\Psr\Request::withProtocolVersion
     *
     * @return void
     */
    public function testWithProtocolVersionChangesTheProtocolVersion()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $request = $request->withProtocolVersion('1.0');

        $this->assertSame('1.0', $request->getProtocolVersion());
    }
}
