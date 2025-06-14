<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Exception\Psr\RequestException;

use Art4\Requests\Exception\Psr\RequestException;
use Psr\Http\Message\RequestInterface;
use WpOrg\Requests\Exception\Transport;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetRequestTest extends TestCase
{
    /**
     * Tests receiving the request when using getRequest().
     *
     * @covers \Art4\Requests\Exception\Psr\RequestException::__construct
     * @covers \Art4\Requests\Exception\Psr\RequestException::getRequest
     */
    public function testGetRequestReturnsRequest(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $previous = $this->createMock(Transport::class);

        $exception = new RequestException($request, $previous);

        TestCase::assertSame($request, $exception->getRequest());
    }
}
