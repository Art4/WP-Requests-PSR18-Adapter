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
     *
     * @return void
     */
    public function testHasHeaderReturnsFalse()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->assertFalse($response->hasHeader('name'));
    }

    /**
     * Tests receiving boolean when using hasHeader().
     *
     * @covers \Art4\Requests\Psr\Response::hasHeader
     *
     * @return void
     */
    public function testHasHeaderReturnsTrue()
    {
        $response = Response::fromResponse(new RequestsResponse());
        $response = $response->withHeader('name', 'value');

        $this->assertTrue($response->hasHeader('name'));
    }

    /**
     * Tests receiving boolean when using hasHeader().
     *
     * @covers \Art4\Requests\Psr\Response::hasHeader
     *
     * @return void
     */
    public function testHasHeaderWithCaseInsensitiveNameReturnsTrue()
    {
        $response = Response::fromResponse(new RequestsResponse());
        $response = $response->withHeader('NAME', 'value');

        $this->assertTrue($response->hasHeader('name'));
    }
}
