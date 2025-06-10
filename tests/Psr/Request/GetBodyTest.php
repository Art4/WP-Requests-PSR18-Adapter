<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Art4\Requests\Psr\Request;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetBodyTest extends TestCase
{
    /**
     * Tests receiving the body when using getBody().
     *
     * @covers \Art4\Requests\Psr\Request::getBody
     */
    public function testGetBodyReturnsString(): void
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        TestCase::assertInstanceOf(StreamInterface::class, $request->getBody());
    }
}
