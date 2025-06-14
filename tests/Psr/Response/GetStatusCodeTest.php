<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use Art4\Requests\Psr\Response;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetStatusCodeTest extends TestCase
{
    /**
     * Tests receiving the status code when using getStatusCode().
     *
     * @covers \Art4\Requests\Psr\Response::getStatusCode
     */
    public function testGetStatusCodeReturnsInteger(): void
    {
        $requestsResponse = new RequestsResponse();
        $requestsResponse->status_code = 200;

        $response = Response::fromResponse($requestsResponse);

        TestCase::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests receiving the status code 200 without code from Requests.
     *
     * @covers \Art4\Requests\Psr\Response::getStatusCode
     */
    public function testGetStatusCodeWithoudCodeFromResponseReturnsInteger(): void
    {
        $requestsResponse = new RequestsResponse();
        $requestsResponse->status_code = false;

        $response = Response::fromResponse($requestsResponse);

        TestCase::assertSame(200, $response->getStatusCode());
    }
}
