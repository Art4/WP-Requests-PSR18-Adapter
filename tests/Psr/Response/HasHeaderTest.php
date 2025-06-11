<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use Art4\Requests\Psr\Response;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class HasHeaderTest extends TestCase
{
    /**
     * Tests receiving boolean when using hasHeader().
     *
     * @covers \Art4\Requests\Psr\Response::hasHeader
     */
    public function testHasHeaderReturnsFalse(): void
    {
        $response = Response::fromResponse(new RequestsResponse());

        TestCase::assertFalse($response->hasHeader('name'));
    }

    /**
     * Tests receiving boolean when using hasHeader().
     *
     * @covers \Art4\Requests\Psr\Response::hasHeader
     */
    public function testHasHeaderReturnsTrue(): void
    {
        $response = Response::fromResponse(new RequestsResponse());
        $response = $response->withHeader('name', 'value');

        TestCase::assertTrue($response->hasHeader('name'));
    }

    /**
     * Tests receiving boolean when using hasHeader().
     *
     * @covers \Art4\Requests\Psr\Response::hasHeader
     */
    public function testHasHeaderWithCaseInsensitiveNameReturnsTrue(): void
    {
        $response = Response::fromResponse(new RequestsResponse());
        $response = $response->withHeader('NAME', 'value');

        TestCase::assertTrue($response->hasHeader('name'));
    }
}
