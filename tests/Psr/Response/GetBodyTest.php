<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use Psr\Http\Message\StreamInterface;
use Art4\Requests\Psr\Response;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetBodyTest extends TestCase
{
    /**
     * Tests receiving the stream when using getBody().
     *
     * @covers \Art4\Requests\Psr\Response::getBody
     *
     * @return void
     */
    public function testGetBodyReturnsStreamInterface()
    {
        $requestsResponse = new RequestsResponse();
        $requestsResponse->status_code = 200;
        $response = Response::fromResponse($requestsResponse);

        $this->assertInstanceOf(StreamInterface::class, $response->getBody());
    }

    /**
     * Tests receiving the stream when using getBody().
     *
     * @covers \Art4\Requests\Psr\Response::getBody
     *
     * @return void
     */
    public function testGetBodyReturnsStreamWithContent()
    {
        $requestsResponse = new RequestsResponse();
        $requestsResponse->body = 'response body';
        $requestsResponse->status_code = 200;
        $response = Response::fromResponse($requestsResponse);

        $this->assertSame('response body', $response->getBody()->__toString());
    }
}
