<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Art4\Requests\Psr\Request;
use Psr\Http\Message\UriInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetHeaderLineTest extends TestCase
{
    /**
     * Tests receiving the header when using getHeaderLine().
     *
     * @covers \Art4\Requests\Psr\Request::getHeaderLine
     *
     * @return void
     */
    public function testGetHeaderLineWithoutHeaderReturnsEmptyString()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertSame('', $request->getHeaderLine('name'));
    }

    /**
     * Tests receiving the header when using getHeaderLine().
     *
     * @covers \Art4\Requests\Psr\Request::getHeaderLine
     *
     * @return void
     */
    public function testGetHeaderLineReturnsString()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));
        $request = $request->withHeader('name', ['value1', 'value2']);

        $this->assertSame('value1,value2', $request->getHeaderLine('name'));
    }

    /**
     * Tests receiving the header when using getHeaderLine().
     *
     * @covers \Art4\Requests\Psr\Request::getHeaderLine
     *
     * @return void
     */
    public function testGetHeaderLineWithCaseInsensitiveNameReturnsString()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));
        $request = $request->withHeader('name', 'value');

        $this->assertSame('value', $request->getHeaderLine('NAME'));
    }
}
