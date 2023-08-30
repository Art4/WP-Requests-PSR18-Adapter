<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Exception\Psr\NetworkException;

use Art4\Requests\Exception\Psr\NetworkException;
use Psr\Http\Message\RequestInterface;
use WpOrg\Requests\Exception\Transport;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetRequestTest extends TestCase
{
    /**
     * Tests receiving the request when using getRequest().
     *
     * @covers \Art4\Requests\Exception\Psr\NetworkException::__construct
     * @covers \Art4\Requests\Exception\Psr\NetworkException::getRequest
     *
     * @return void
     */
    public function testGetRequestReturnsRequest()
    {
        $request = $this->createMock(RequestInterface::class);
        $previous = $this->createMock(Transport::class);

        $exception = new NetworkException($request, $previous);

        $this->assertSame($request, $exception->getRequest());
    }
}
