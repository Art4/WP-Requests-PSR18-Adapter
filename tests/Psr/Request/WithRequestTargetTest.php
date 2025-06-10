<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Art4\Requests\Psr\Request;
use Art4\Requests\Tests\TypeProviderHelper;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithRequestTargetTest extends TestCase
{
    /**
     * Tests changing the request-target when using withRequestTarget().
     *
     * @covers \Art4\Requests\Psr\Request::withRequestTarget
     */
    public function testWithRequestTargetReturnsRequest(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        TestCase::assertInstanceOf(RequestInterface::class, $request->withRequestTarget('/'));
    }

    /**
     * Tests changing the request-target when using withRequestTarget().
     *
     * @covers \Art4\Requests\Psr\Request::withRequestTarget
     */
    public function testWithRequestTargetReturnsNewInstance(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        TestCase::assertNotSame($request, $request->withRequestTarget('/'));
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
     */
    #[DataProvider('dataValidRequestTarget')]
    public function testWithRequestTargetChangesTheRequestTarget($input, $expected): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $request = $request->withRequestTarget($input);

        TestCase::assertSame($expected, $request->getRequestTarget());
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
