<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Art4\Requests\Psr\Response;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class WithAddedHeaderTest extends TestCase
{
    /**
     * Tests changing the header when using withAddedHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withAddedHeader
     *
     * @return void
     */
    public function testWithAddedHeaderReturnsResponseInterface()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->assertInstanceOf(ResponseInterface::class, $response->withAddedHeader('name', 'value'));
    }

    /**
     * Tests changing the header when using withAddedHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withAddedHeader
     *
     * @return void
     */
    public function testWithAddedHeaderReturnsNewInstance()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->assertNotSame($response, $response->withAddedHeader('name', 'value'));
    }

    /**
     * Data Provider.
     *
     * @return array<string, mixed>
     */
    public static function dataInvalidTypeNotString(): array
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_STRING);
    }

    /**
     * Tests receiving an exception when the withAddedHeader() method received an invalid input type as `$value`.
     *
     * @dataProvider dataInvalidTypeNotStringOrArray
     *
     * @covers \Art4\Requests\Psr\Response::withAddedHeader
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    public function testWithAddedHeaderWithoutValueAsStringOrArrayThrowsInvalidArgumentException($input)
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withAddedHeader(): Argument #2 ($value) must be of type string|array', Response::class));

        $response->withAddedHeader('name', $input);
    }

    /**
     * Data Provider.
     *
     * @return array<string, mixed>
     */
    public static function dataInvalidTypeNotStringOrArray()
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_STRING, TypeProviderHelper::GROUP_ARRAY);
    }

    /**
     * Tests receiving an exception when the withAddedHeader() method received an invalid input type as `$value`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Response::withAddedHeader
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    public function testWithAddedHeaderWithoutValueAsStringInArrayThrowsInvalidArgumentException($input)
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withAddedHeader(): Argument #2 ($value) must be of type string|array containing strings', Response::class));

        $response->withAddedHeader('name', [$input]);
    }

    /**
     * Tests changing the header when using withAddedHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withAddedHeader
     *
     * @return void
     */
    public function testWithAddedHeaderChangesTheHeaders()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $response = $response->withAddedHeader('Name', 'value');

        $this->assertSame(['Name' => ['value']], $response->getHeaders());
    }

    /**
     * Tests changing the header when using withAddedHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withAddedHeader
     *
     * @return void
     */
    public function testWithAddedHeaderCaseInsensitiveChangesTheHeaders()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $response = $response->withAddedHeader('name', 'value1');
        $response = $response->withAddedHeader('NAME', 'value2');

        $this->assertSame(['NAME' => ['value1', 'value2']], $response->getHeaders());
    }
}
