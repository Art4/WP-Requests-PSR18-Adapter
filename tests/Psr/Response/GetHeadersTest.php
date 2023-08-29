<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use Art4\Requests\Psr\Response;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetHeadersTest extends TestCase
{
    /**
     * Tests receiving the headers when using getHeaders().
     *
     * @covers \Art4\Requests\Psr\Response::fromResponse
     * @covers \Art4\Requests\Psr\Response::getHeaders
     *
     * @return void
     */
    public function _testGetHeadersReturnsEmptyArray()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->assertSame([], $response->getHeaders());
    }

    /**
     * Tests receiving the headers when using getHeaders().
     *
     * @covers \Art4\Requests\Psr\Response::fromResponse
     * @covers \Art4\Requests\Psr\Response::getHeaders
     *
     * @return void
     */
    public function testGetHeadersReturnsArray()
    {
        $requestsResponse = new RequestsResponse();
        $requestsResponse->headers['name'] = 'value';

        $response = Response::fromResponse($requestsResponse);

        $this->assertSame(['name' => ['value']], $response->getHeaders());
    }
}
