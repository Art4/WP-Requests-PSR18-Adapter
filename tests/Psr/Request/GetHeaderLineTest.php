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
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        TestCase::assertSame('', $request->getHeaderLine('name'));
    }

    /**
     * Tests receiving the header when using getHeaderLine().
     *
     * @covers \Art4\Requests\Psr\Request::getHeaderLine
     */
    public function testGetHeaderLineReturnsString(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));
        $request = $request->withHeader('name', ['value1', 'value2']);

        TestCase::assertSame('value1,value2', $request->getHeaderLine('name'));
    }

    /**
     * Tests receiving the header when using getHeaderLine().
     *
     * @covers \Art4\Requests\Psr\Request::getHeaderLine
     */
    public function testGetHeaderLineWithCaseInsensitiveNameReturnsString(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));
        $request = $request->withHeader('name', 'value');

        TestCase::assertSame('value', $request->getHeaderLine('NAME'));
    }
}
