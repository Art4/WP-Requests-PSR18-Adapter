<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Art4\Requests\Psr\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithMethodTest extends TestCase
{
    /**
     * Tests changing the method when using withMethod().
     *
     * @covers \Art4\Requests\Psr\Request::withMethod
     */
    public function testWithMethodReturnsRequest(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        TestCase::assertInstanceOf(RequestInterface::class, $request->withMethod('GET'));
    }

    /**
     * Tests changing the method when using withMethod().
     *
     * @covers \Art4\Requests\Psr\Request::withMethod
     */
    public function testWithMethodReturnsNewInstance(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        TestCase::assertNotSame($request, $request->withMethod('GET'));
    }

    /**
     * Tests using withMethod() with empty string.
     *
     * @covers \Art4\Requests\Psr\Request::withMethod
     */
    public function testWithMethodWithEmptyStringThrowsException(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        TestCase::expectException(\InvalidArgumentException::class);
        TestCase::expectExceptionMessage('Method must be a non-empty string');

        $request->withMethod('');
    }

    /**
     * Tests changing the method when using withMethod().
     *
     * @dataProvider dataValidMethod
     *
     * @covers \Art4\Requests\Psr\Request::withMethod
     *
     * @param string $expected
     */
    #[DataProvider('dataValidMethod')]
    public function testWithMethodChangesTheMethod(string $input, $expected): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $request = $request->withMethod($input);

        TestCase::assertSame($expected, $request->getMethod());
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
        ];
    }
}
