<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Request;

use Psr\Http\Message\UriInterface;
use Art4\Requests\Psr\Request;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetMethodTest extends TestCase
{
    /**
     * Tests receiving the method when using getMethod().
     *
     * @covers \Art4\Requests\Psr\Request::getMethod
     *
     * @return void
     */
    public function testGetMethodReturnsString()
    {
        $request = Request::withMethodAndUri('GET', $this->createMock(UriInterface::class));

        $this->assertSame('GET', $request->getMethod());
    }
}
