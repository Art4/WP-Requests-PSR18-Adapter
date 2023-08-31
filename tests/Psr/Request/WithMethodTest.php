<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Art4\Requests\Psr\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithMethodTest extends TestCase
{
    /**
     * Tests changing the method when using withMethod().
     *
     * @covers \Art4\Requests\Psr\Request::withMethod
     *
     * @return void
     */
    public function testWithMethodReturnsRequest()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertInstanceOf(RequestInterface::class, $request->withMethod('GET'));
    }

    /**
     * Tests changing the method when using withMethod().
     *
     * @covers \Art4\Requests\Psr\Request::withMethod
     *
     * @return void
     */
    public function testWithMethodReturnsNewInstance()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertNotSame($request, $request->withMethod('GET'));
    }

    /**
     * Tests changing the method when using withMethod().
     *
     * @dataProvider dataValidMethod
     *
     * @covers \Art4\Requests\Psr\Request::withMethod
     *
     * @param string $input
     * @param string $expected
     *
     * @return void
     */
    public function testWithMethodChangesTheMethod($input, $expected)
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $request = $request->withMethod($input);

        $this->assertSame($expected, $request->getMethod());
    }

    /**
     * Data Provider.
     *
     * @return array<string,string[]>
     */
    public static function dataValidMethod(): array
    {
        return [
            'Return an instance with the provided HTTP method' => ['POST', 'POST'],
            'implementations SHOULD NOT modify the given string' => ['Head', 'Head'],
            'do not throw InvalidArgumentException for invalid HTTP methods' => ['foobar', 'foobar'],
            'do not throw InvalidArgumentException for empty methods' => ['', ''],
        ];
    }
}
