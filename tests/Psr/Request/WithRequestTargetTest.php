<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Art4\Requests\Psr\Request;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class WithRequestTargetTest extends TestCase
{
    /**
     * Tests changing the request-target when using withRequestTarget().
     *
     * @covers \Art4\Requests\Psr\Request::withRequestTarget
     *
     * @return void
     */
    public function testWithRequestTargetReturnsRequest()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertInstanceOf(RequestInterface::class, $request->withRequestTarget('/'));
    }

    /**
     * Tests changing the request-target when using withRequestTarget().
     *
     * @covers \Art4\Requests\Psr\Request::withRequestTarget
     *
     * @return void
     */
    public function testWithRequestTargetReturnsNewInstance()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertNotSame($request, $request->withRequestTarget('/'));
    }

    /**
     * Tests changing the request-target when using withRequestTarget().
     *
     * @dataProvider dataValidRequestTarget
     *
     * @covers \Art4\Requests\Psr\Request::withRequestTarget
     * @covers \Art4\Requests\Psr\Request::getRequestTarget
     *
     * @param string $input
     * @param string $expected
     *
     * @return void
     */
    public function testWithRequestTargetChangesTheRequestTarget($input, $expected)
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $request = $request->withRequestTarget($input);

        $this->assertSame($expected, $request->getRequestTarget());
    }

    /**
     * Data Provider.
     *
     * @return array<string,string[]>
     */
    public static function dataValidRequestTarget(): array
    {
        return [
            'Return an instance with the specific request-target' => ['path', 'path'],
            'Return an instance with origin-form' => ['absolute-path?query', 'absolute-path?query'],
            'Return an instance with absolute-form' => ['http://www.example.org/pub/WWW/TheProject.html', 'http://www.example.org/pub/WWW/TheProject.html'],
            'Return an instance with authority-form' => ['www.example.com:80', 'www.example.com:80'],
            'Return an instance with asterisk-form' => ['*', '*'],
            'If no request-target has been specifically provided, this method MUST return the string "/".' => ['', '/'],
        ];
    }
}
