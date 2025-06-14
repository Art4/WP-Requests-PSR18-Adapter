<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use Art4\Requests\Psr\Response;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetHeaderTest extends TestCase
{
    /**
     * Tests receiving the header when using getHeader().
     *
     * @covers \Art4\Requests\Psr\Response::getHeader
     */
    public function testGetHeaderWithoutHeaderReturnsEmptyArray(): void
    {
        $response = Response::fromResponse(new RequestsResponse());

        TestCase::assertSame([], $response->getHeader('name'));
    }

    /**
     * Tests receiving the header when using getHeader().
     *
     * @covers \Art4\Requests\Psr\Response::getHeader
     */
    public function testGetHeaderReturnsArray(): void
    {
        $response = Response::fromResponse(new RequestsResponse());
        $response = $response->withHeader('name', 'value');

        TestCase::assertSame(['value'], $response->getHeader('name'));
    }

    /**
     * Tests receiving the header when using getHeader().
     *
     * @covers \Art4\Requests\Psr\Response::getHeader
     */
    public function testGetHeaderWithCaseInsensitiveNameReturnsArray(): void
    {
        $response = Response::fromResponse(new RequestsResponse());
        $response = $response->withHeader('name', 'value');

        TestCase::assertSame(['value'], $response->getHeader('NAME'));
    }
}
