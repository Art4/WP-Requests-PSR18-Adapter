<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Art4\Requests\Psr\Request;
use Psr\Http\Message\UriInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetHeaderTest extends TestCase
{
    /**
     * Tests receiving the header when using getHeader().
     *
     * @covers \Art4\Requests\Psr\Request::getHeader
     */
    public function testGetHeaderWithoutHeaderReturnsEmptyArray(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        TestCase::assertSame([], $request->getHeader('name'));
    }

    /**
     * Tests receiving the header when using getHeader().
     *
     * @covers \Art4\Requests\Psr\Request::getHeader
     */
    public function testGetHeaderReturnsArray(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));
        $request = $request->withHeader('name', 'value');

        TestCase::assertSame(['value'], $request->getHeader('name'));
    }

    /**
     * Tests receiving the header when using getHeader().
     *
     * @covers \Art4\Requests\Psr\Request::getHeader
     */
    public function testGetHeaderWithCaseInsensitiveNameReturnsArray(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));
        $request = $request->withHeader('name', 'value');

        TestCase::assertSame(['value'], $request->getHeader('NAME'));
    }
}
