<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Art4\Requests\Psr\Request;
use Psr\Http\Message\UriInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetUriTest extends TestCase
{
    /**
     * Tests receiving the uri when using getUri().
     *
     * @covers \Art4\Requests\Psr\Request::getUri
     */
    public function testGetUriReturnsUriInterface(): void
    {
        $uri = self::createStub(UriInterface::class);

        $request = Request::withMethodAndUri('GET', $uri);

        self::assertSame($uri, $request->getUri());
    }
}
