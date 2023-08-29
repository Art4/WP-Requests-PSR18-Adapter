<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Psr\Http\Message\UriInterface;
use Art4\Requests\Psr\Request;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetRequestTargetTest extends TestCase
{
    /**
     * Tests receiving the request target when using getRequestTarget().
     *
     * @dataProvider dataGetRequestTarget
     *
     * @covers \Art4\Requests\Psr\Request::getRequestTarget
     *
     * @return void
     */
    public function testGetRequestTargetReturnsString(string $path, string $query, string $expected)
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn($path);
        $uri->method('getQuery')->willReturn($query);

        $request = Request::withMethodAndUri('GET', $uri);

        $this->assertSame($expected, $request->getRequestTarget());
    }

    /**
     * Data Provider.
     *
     * @return array<string,string[]>
     */
    public static function dataGetRequestTarget(): array
    {
        return [
            'Retrieves the message request target' => ['path', '', 'path'],
            'Retrieves the message request target with query' => ['path', 'foo=bar', 'path?foo=bar'],
            'If no URI is available, this method MUST return the string "/"' => ['', '', '/'],
            'If no URI is available, this method MUST return the string "/" with query' => ['', 'foo=bar', '/?foo=bar'],
        ];
    }
}
