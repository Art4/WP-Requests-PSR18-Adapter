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
     *
     * @return void
     */
    public function testGetHeaderWithoutHeaderReturnsEmptyArray()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertSame([], $request->getHeader('name'));
    }

    /**
     * Tests receiving the header when using getHeader().
     *
     * @covers \Art4\Requests\Psr\Request::getHeader
     *
     * @return void
     */
    public function testGetHeaderReturnsArray()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));
        $request = $request->withHeader('name', 'value');

        $this->assertSame(['value'], $request->getHeader('name'));
    }

    /**
     * Tests receiving the header when using getHeader().
     *
     * @covers \Art4\Requests\Psr\Request::getHeader
     *
     * @return void
     */
    public function testGetHeaderWithCaseInsensitiveNameReturnsArray()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));
        $request = $request->withHeader('name', 'value');

        $this->assertSame(['value'], $request->getHeader('NAME'));
    }
}
