<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Psr\Http\Message\UriInterface;
use Art4\Requests\Psr\Request;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetUriTest extends TestCase
{
    /**
     * Tests receiving the uri when using getUri().
     *
     * @covers \Art4\Requests\Psr\Request::getUri
     *
     * @return void
     */
    public function testGetUriReturnsUriInterface()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertInstanceOf(UriInterface::class, $request->getUri());
    }
}
