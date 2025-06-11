<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use Art4\Requests\Psr\Response;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetHeaderLineTest extends TestCase
{
    /**
     * Tests receiving the header when using getHeaderLine().
     *
     * @covers \Art4\Requests\Psr\Response::getHeaderLine
     */
    public function testGetHeaderLineWithoutHeaderReturnsEmptyString(): void
    {
        $response = Response::fromResponse(new RequestsResponse());

        TestCase::assertSame('', $response->getHeaderLine('name'));
    }

    /**
     * Tests receiving the header when using getHeaderLine().
     *
     * @covers \Art4\Requests\Psr\Response::getHeaderLine
     */
    public function testGetHeaderLineReturnsString(): void
    {
        $response = Response::fromResponse(new RequestsResponse());
        $response = $response->withHeader('name', ['value1', 'value2']);

        TestCase::assertSame('value1,value2', $response->getHeaderLine('name'));
    }

    /**
     * Tests receiving the header when using getHeaderLine().
     *
     * @covers \Art4\Requests\Psr\Response::getHeaderLine
     */
    public function testGetHeaderLineWithCaseInsensitiveNameReturnsString(): void
    {
        $response = Response::fromResponse(new RequestsResponse());
        $response = $response->withHeader('name', 'value');

        TestCase::assertSame('value', $response->getHeaderLine('NAME'));
    }
}
