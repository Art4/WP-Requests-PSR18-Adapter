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
     */
    public function testGetHeaderLineWithoutHeaderReturnsEmptyString(): void
    {
        $request = Request::withMethodAndUri('GET', self::createStub(UriInterface::class));

        self::assertSame('', $request->getHeaderLine('name'));
    }

    /**
     * Tests receiving the header when using getHeaderLine().
     *
     * @covers \Art4\Requests\Psr\Request::getHeaderLine
     */
    public function testGetHeaderLineReturnsString(): void
    {
        $request = Request::withMethodAndUri('GET', self::createStub(UriInterface::class));
        $request = $request->withHeader('name', ['value1', 'value2']);

        self::assertSame('value1,value2', $request->getHeaderLine('name'));
    }

    /**
     * Tests receiving the header when using getHeaderLine().
     *
     * @covers \Art4\Requests\Psr\Request::getHeaderLine
     */
    public function testGetHeaderLineWithCaseInsensitiveNameReturnsString(): void
    {
        $request = Request::withMethodAndUri('GET', self::createStub(UriInterface::class));
        $request = $request->withHeader('name', 'value');

        self::assertSame('value', $request->getHeaderLine('NAME'));
    }
}
