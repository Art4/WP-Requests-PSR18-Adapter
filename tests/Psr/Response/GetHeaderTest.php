<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use Art4\Requests\Psr\Response;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetHeaderTest extends TestCase
{
    /**
     * Tests receiving the header when using getHeader().
     *
     * @covers \Art4\Requests\Psr\Response::getHeader
     *
     * @return void
     */
    public function testGetHeaderWithoutHeaderReturnsEmptyArray()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->assertSame([], $response->getHeader('name'));
    }

    /**
     * Tests receiving the header when using getHeader().
     *
     * @covers \Art4\Requests\Psr\Response::getHeader
     *
     * @return void
     */
    public function testGetHeaderReturnsArray()
    {
        $response = Response::fromResponse(new RequestsResponse());
        $response = $response->withHeader('name', 'value');

        $this->assertSame(['value'], $response->getHeader('name'));
    }

    /**
     * Tests receiving the header when using getHeader().
     *
     * @covers \Art4\Requests\Psr\Response::getHeader
     *
     * @return void
     */
    public function testGetHeaderWithCaseInsensitiveNameReturnsArray()
    {
        $response = Response::fromResponse(new RequestsResponse());
        $response = $response->withHeader('name', 'value');

        $this->assertSame(['value'], $response->getHeader('NAME'));
    }
}
