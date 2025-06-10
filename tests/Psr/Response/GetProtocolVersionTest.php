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
     */
    public function testGetProtocolVersionWithFloatReturnsString(): void
    {
        $requestsResponse = new RequestsResponse();
        $requestsResponse->status_code = 200;
        $requestsResponse->protocol_version = 1.0;
        $response = Response::fromResponse($requestsResponse);

        TestCase::assertSame('1.0', $response->getProtocolVersion());
    }

    /**
     * Tests receiving the protocol version when using getProtocolVersion().
     *
     * @covers \Art4\Requests\Psr\Response::getProtocolVersion
     */
    public function testGetProtocolVersionWithFalseReturnsString(): void
    {
        $requestsResponse = new RequestsResponse();
        $requestsResponse->status_code = 200;
        $requestsResponse->protocol_version = false;
        $response = Response::fromResponse($requestsResponse);

        TestCase::assertSame('1.1', $response->getProtocolVersion());
    }
}
