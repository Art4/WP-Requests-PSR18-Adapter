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
     *
     * @return void
     */
    public function testGetHeaderLineWithoutHeaderReturnsEmptyString()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->assertSame('', $response->getHeaderLine('name'));
    }

    /**
     * Tests receiving the header when using getHeaderLine().
     *
     * @covers \Art4\Requests\Psr\Response::getHeaderLine
     *
     * @return void
     */
    public function testGetHeaderLineReturnsString()
    {
        $response = Response::fromResponse(new RequestsResponse());
        $response = $response->withHeader('name', ['value1', 'value2']);

        $this->assertSame('value1,value2', $response->getHeaderLine('name'));
    }

    /**
     * Tests receiving the header when using getHeaderLine().
     *
     * @covers \Art4\Requests\Psr\Response::getHeaderLine
     *
     * @return void
     */
    public function testGetHeaderLineWithCaseInsensitiveNameReturnsString()
    {
        $response = Response::fromResponse(new RequestsResponse());
        $response = $response->withHeader('name', 'value');

        $this->assertSame('value', $response->getHeaderLine('NAME'));
    }
}
