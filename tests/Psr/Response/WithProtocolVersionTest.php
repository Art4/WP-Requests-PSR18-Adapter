<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use Art4\Requests\Psr\Response;
use Psr\Http\Message\ResponseInterface;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithProtocolVersionTest extends TestCase
{
    /**
     * Tests changing the protocol version when using withProtocolVersion().
     *
     * @covers \Art4\Requests\Psr\Response::withProtocolVersion
     */
    public function testWithProtocolVersionReturnsResponse(): void
    {
        $requestsResponse = new RequestsResponse();
        $response = Response::fromResponse($requestsResponse);

        TestCase::assertInstanceOf(ResponseInterface::class, $response->withProtocolVersion('1.0'));
    }

    /**
     * Tests changing the protocol version when using withProtocolVersion().
     *
     * @covers \Art4\Requests\Psr\Response::withProtocolVersion
     */
    public function testWithProtocolVersionReturnsNewInstance(): void
    {
        $requestsResponse = new RequestsResponse();
        $response = Response::fromResponse($requestsResponse);

        TestCase::assertNotSame($response, $response->withProtocolVersion('1.0'));
    }

    /**
     * Tests changing the version when using withProtocolVersion().
     *
     * @covers \Art4\Requests\Psr\Response::withProtocolVersion
     */
    public function testWithProtocolVersionChangesTheProtocolVersion(): void
    {
        $requestsResponse = new RequestsResponse();
        $response = Response::fromResponse($requestsResponse);

        $response = $response->withProtocolVersion('1.0');

        TestCase::assertSame('1.0', $response->getProtocolVersion());
    }
}
