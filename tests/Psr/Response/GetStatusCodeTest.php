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
     *
     * @return void
     */
    public function testGetStatusCodeReturnsInteger()
    {
        $requestsResponse = new RequestsResponse();
        $requestsResponse->status_code = 200;
        $response = Response::fromResponse($requestsResponse);

        $this->assertSame(200, $response->getStatusCode());
    }
}
