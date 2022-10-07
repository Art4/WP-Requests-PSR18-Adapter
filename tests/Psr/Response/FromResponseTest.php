<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use Psr\Http\Message\ResponseInterface;
use Art4\Requests\Psr\Response;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class FromResponseTest extends TestCase
{
    /**
     * Tests receiving a Response instance when using fromResponse().
     *
     * @covers \Art4\Requests\Psr\Request::fromResponse
     *
     * @return void
     */
    public function testFromResponseReturnsResponseInterface()
    {
        $requestsResponse = new RequestsResponse();

        $this->assertInstanceOf(
            ResponseInterface::class,
            Response::fromResponse($requestsResponse)
        );
    }
}
