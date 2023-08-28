<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Art4\Requests\Psr\Response;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class WithProtocolVersionTest extends TestCase
{
    /**
     * Tests changing the protocol version when using withProtocolVersion().
     *
     * @covers \Art4\Requests\Psr\Response::withProtocolVersion
     *
     * @return void
     */
    public function testWithProtocolVersionReturnsResponse()
    {
        $requestsResponse = new RequestsResponse();
        $response = Response::fromResponse($requestsResponse);

        $this->assertInstanceOf(ResponseInterface::class, $response->withProtocolVersion('1.0'));
    }

    /**
     * Tests changing the protocol version when using withProtocolVersion().
     *
     * @covers \Art4\Requests\Psr\Response::withProtocolVersion
     *
     * @return void
     */
    public function testWithProtocolVersionReturnsNewInstance()
    {
        $requestsResponse = new RequestsResponse();
        $response = Response::fromResponse($requestsResponse);

        $this->assertNotSame($response, $response->withProtocolVersion('1.0'));
    }

    /**
     * Data Provider.
     *
     * @return array
     */
    public function dataInvalidTypeNotString()
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_STRING);
    }

    /**
     * Tests changing the version when using withProtocolVersion().
     *
     * @covers \Art4\Requests\Psr\Response::withProtocolVersion
     *
     * @return void
     */
    public function testWithProtocolVersionChangesTheProtocolVersion()
    {
        $requestsResponse = new RequestsResponse();
        $response = Response::fromResponse($requestsResponse);

        $response = $response->withProtocolVersion('1.0');

        $this->assertSame('1.0', $response->getProtocolVersion());
    }
}
