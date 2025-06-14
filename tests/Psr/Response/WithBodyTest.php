<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use Art4\Requests\Psr\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithBodyTest extends TestCase
{
    /**
     * Tests changing the body when using withBody().
     *
     * @covers \Art4\Requests\Psr\Response::withBody
     */
    public function testWithBodyReturnsResponse(): void
    {
        $requestsResponse = new RequestsResponse();
        $response = Response::fromResponse($requestsResponse);

        TestCase::assertInstanceOf(
            ResponseInterface::class,
            $response->withBody($this->createMock(StreamInterface::class))
        );
    }

    /**
     * Tests changing the protocol version when using withBody().
     *
     * @covers \Art4\Requests\Psr\Response::withBody
     */
    public function testWithBodyReturnsNewInstance(): void
    {
        $requestsResponse = new RequestsResponse();
        $response = Response::fromResponse($requestsResponse);

        TestCase::assertNotSame(
            $response,
            $response->withBody($this->createMock(StreamInterface::class))
        );
    }
}
