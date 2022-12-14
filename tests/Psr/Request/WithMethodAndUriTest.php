<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Art4\Requests\Psr\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use WpOrg\Requests\Exception\InvalidArgument;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class WithMethodAndUriTest extends TestCase
{
    /**
     * Tests receiving a Request instance when using withMethodAndUri().
     *
     * @covers \Art4\Requests\Psr\Request::withMethodAndUri
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
     * Tests receiving an exception when the withMethodAndUri() method received an invalid input type as `$method`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Request::withMethodAndUri
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    public function testWithMethodAndUriWithoutMethodStringThrowsException($input)
    {
        $uri = $this->createMock(UriInterface::class);

        $this->expectException(InvalidArgument::class);
        $this->expectExceptionMessage(sprintf('%s::withMethodAndUri(): Argument #1 ($method) must be of type string', Request::class));

        Request::withMethodAndUri($input, $uri);
    }

    /**
     * Data Provider.
     *
     * @return array
     */
    public function dataInvalidTypeNotString()
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_STRING);
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
