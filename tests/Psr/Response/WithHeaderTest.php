<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use Art4\Requests\Psr\Response;
use Art4\Requests\Tests\TypeProviderHelper;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Http\Message\ResponseInterface;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithHeaderTest extends TestCase
{
    /**
     * Tests changing the header when using withHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withHeader
     *
     * @return void
     */
    public function testWithHeaderReturnsResponseInterface()
    {
        $response = Response::fromResponse(new RequestsResponse());

        TestCase::assertInstanceOf(ResponseInterface::class, $response->withHeader('name', 'value'));
    }

    /**
     * Tests changing the header when using withHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withHeader
     *
     * @return void
     */
    public function testWithHeaderReturnsNewInstance()
    {
        $response = Response::fromResponse(new RequestsResponse());

        TestCase::assertNotSame($response, $response->withHeader('name', 'value'));
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
     * Tests receiving an exception when the withHeader() method received an invalid input type as `$value`.
     *
     * @dataProvider dataInvalidTypeNotStringOrArray
     *
     * @covers \Art4\Requests\Psr\Response::withHeader
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    #[DataProvider('dataInvalidTypeNotStringOrArray')]
    public function testWithHeaderWithoutValueAsStringOrArrayThrowsInvalidArgumentException($input)
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withHeader(): Argument #2 ($value) must be of type string|array', Response::class));

        $response->withHeader('name', $input);
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
     * Tests receiving an exception when the withHeader() method received an invalid input type as `$value`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Response::withHeader
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    #[DataProvider('dataInvalidTypeNotString')]
    public function testWithHeaderWithoutValueAsStringInArrayThrowsInvalidArgumentException($input)
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withHeader(): Argument #2 ($value) must be of type string|array containing strings', Response::class));

        $response->withHeader('name', [$input]);
    }

    /**
     * Tests changing the header when using withHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withHeader
     *
     * @return void
     */
    public function testWithHeaderChangesTheHeaders()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $response = $response->withHeader('Name', 'value');

        TestCase::assertSame(['Name' => ['value']], $response->getHeaders());
    }

    /**
     * Tests changing the header when using withHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withHeader
     *
     * @return void
     */
    public function testWithHeaderCaseInsensitiveChangesTheHeaders()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $response = $response->withHeader('name', 'value');
        $response = $response->withHeader('NAME', 'value');

        TestCase::assertSame(['NAME' => ['value']], $response->getHeaders());
    }
}
