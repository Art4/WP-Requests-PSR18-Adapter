<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use Art4\Requests\Psr\Response;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetProtocolVersionTest extends TestCase
{
    /**
     * Tests receiving the protocol version when using getProtocolVersion().
     *
     * @covers \Art4\Requests\Psr\Response::fromResponse
     * @covers \Art4\Requests\Psr\Response::getProtocolVersion
     *
     * @return void
     */
    public function testGetProtocolVersionWithFloatReturnsString()
    {
        $requestsResponse = new RequestsResponse();
        $requestsResponse->status_code = 200;
        $requestsResponse->protocol_version = 1.0;
        $response = Response::fromResponse($requestsResponse);

        $this->assertSame('1.0', $response->getProtocolVersion());
    }

    /**
     * Tests receiving the protocol version when using getProtocolVersion().
     *
     * @covers \Art4\Requests\Psr\Response::getProtocolVersion
     *
     * @return void
     */
    public function testGetProtocolVersionWithFalseReturnsString()
    {
        $requestsResponse = new RequestsResponse();
        $requestsResponse->status_code = 200;
        $requestsResponse->protocol_version = false;
        $response = Response::fromResponse($requestsResponse);

        $this->assertSame('1.1', $response->getProtocolVersion());
    }
}
