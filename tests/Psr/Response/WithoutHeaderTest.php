<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Art4\Requests\Psr\Response;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class WithoutHeaderTest extends TestCase
{
    /**
     * Tests removing the header when using withoutHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withoutHeader
     *
     * @return void
     */
    public function testWithoutHeaderReturnsResponseInterface()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->assertInstanceOf(ResponseInterface::class, $response->withoutHeader('name'));
    }

    /**
     * Tests removing the header when using withoutHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withoutHeader
     *
     * @return void
     */
    public function testWithoutHeaderReturnsNewInstance()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->assertNotSame($response, $response->withoutHeader('name'));
    }

    /**
     * Data Provider.
     *
     * @return array<string, mixed>
     */
    public function dataInvalidTypeNotString()
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_STRING);
    }

    /**
     * Tests removing the header when using withoutHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withoutHeader
     *
     * @return void
     */
    public function testWithoutHeaderChangesTheHeaders()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $response = $response->withHeader('Name', 'value');
        $response = $response->withoutHeader('Name');

        $this->assertSame([], $response->getHeaders());
    }

    /**
     * Tests removing the header when using withoutHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withoutHeader
     *
     * @return void
     */
    public function testWithoutHeaderCaseInsensitiveChangesTheHeaders()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $response = $response->withHeader('NAME1', 'value1');
        $response = $response->withHeader('NAME2', 'value2');
        $response = $response->withoutHeader('name1');

        $this->assertSame(['NAME2' => ['value2']], $response->getHeaders());
    }
}
