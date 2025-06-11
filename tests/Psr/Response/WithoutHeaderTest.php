<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use Art4\Requests\Psr\Response;
use Psr\Http\Message\ResponseInterface;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithoutHeaderTest extends TestCase
{
    /**
     * Tests removing the header when using withoutHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withoutHeader
     */
    public function testWithoutHeaderReturnsResponseInterface(): void
    {
        $response = Response::fromResponse(new RequestsResponse());

        TestCase::assertInstanceOf(ResponseInterface::class, $response->withoutHeader('name'));
    }

    /**
     * Tests removing the header when using withoutHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withoutHeader
     */
    public function testWithoutHeaderReturnsNewInstance(): void
    {
        $response = Response::fromResponse(new RequestsResponse());

        TestCase::assertNotSame($response, $response->withoutHeader('name'));
    }

    /**
     * Tests removing the header when using withoutHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withoutHeader
     */
    public function testWithoutHeaderChangesTheHeaders(): void
    {
        $response = Response::fromResponse(new RequestsResponse());

        $response = $response->withHeader('Name', 'value');
        $response = $response->withoutHeader('Name');

        TestCase::assertSame([], $response->getHeaders());
    }

    /**
     * Tests removing the header when using withoutHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withoutHeader
     */
    public function testWithoutHeaderCaseInsensitiveChangesTheHeaders(): void
    {
        $response = Response::fromResponse(new RequestsResponse());

        $response = $response->withHeader('NAME1', 'value1');
        $response = $response->withHeader('NAME2', 'value2');
        $response = $response->withoutHeader('name1');

        TestCase::assertSame(['NAME2' => ['value2']], $response->getHeaders());
    }
}
