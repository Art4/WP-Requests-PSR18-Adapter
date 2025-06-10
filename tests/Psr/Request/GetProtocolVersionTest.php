<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Art4\Requests\Psr\Request;
use Psr\Http\Message\UriInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetProtocolVersionTest extends TestCase
{
    /**
     * Tests receiving the protocol version when using getProtocolVersion().
     *
     * @covers \Art4\Requests\Psr\Request::getProtocolVersion
     *
     * @return void
     */
    public function testGetProtocolVersionReturnsString()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        TestCase::assertSame('1.1', $request->getProtocolVersion());
    }
}
