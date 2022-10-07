<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Art4\Requests\Psr\Request;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetBodyTest extends TestCase
{
    /**
     * Tests receiving the body when using getBody().
     *
     * @covers \Art4\Requests\Psr\Request::getBody
     *
     * @return void
     */
    public function testGetBodyReturnsString()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertInstanceOf(StreamInterface::class, $request->getBody());
    }
}
