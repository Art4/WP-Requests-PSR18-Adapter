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
     */
    public function testWithProtocolVersionReturnsRequest(): void
    {
        $request = Request::withMethodAndUri('GET', self::createStub(UriInterface::class));

        self::assertInstanceOf(RequestInterface::class, $request->withProtocolVersion('1.0'));
    }

    /**
     * Tests changing the version when using withProtocolVersion().
     *
     * @covers \Art4\Requests\Psr\Request::withProtocolVersion
     */
    public function testWithProtocolVersionReturnsNewInstance(): void
    {
        $request = Request::withMethodAndUri('GET', self::createStub(UriInterface::class));

        self::assertNotSame($request, $request->withProtocolVersion('1.0'));
    }

    /**
     * Tests changing the version when using withProtocolVersion().
     *
     * @covers \Art4\Requests\Psr\Request::withProtocolVersion
     */
    public function testWithProtocolVersionChangesTheProtocolVersion(): void
    {
        $request = Request::withMethodAndUri('GET', self::createStub(UriInterface::class));

        $request = $request->withProtocolVersion('1.0');

        self::assertSame('1.0', $request->getProtocolVersion());
    }
}
