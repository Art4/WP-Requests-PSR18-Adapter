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

final class WithAddedHeaderTest extends TestCase
{
    /**
     * Tests changing the header when using withAddedHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withAddedHeader
     */
    public function testWithAddedHeaderReturnsResponseInterface(): void
    {
        $response = Response::fromResponse(new RequestsResponse());

        TestCase::assertInstanceOf(ResponseInterface::class, $response->withAddedHeader('name', 'value'));
    }

    /**
     * Tests changing the header when using withAddedHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withAddedHeader
     */
    public function testWithAddedHeaderReturnsNewInstance(): void
    {
        $response = Response::fromResponse(new RequestsResponse());

        TestCase::assertNotSame($response, $response->withAddedHeader('name', 'value'));
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
     */
    #[DataProvider('dataInvalidTypeNotStringOrArray')]
    public function testWithAddedHeaderWithoutValueAsStringOrArrayThrowsInvalidArgumentException($input): void
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
    public static function dataInvalidTypeNotStringOrArray(): array
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
     */
    #[DataProvider('dataInvalidTypeNotString')]
    public function testWithAddedHeaderWithoutValueAsStringInArrayThrowsInvalidArgumentException($input): void
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
     */
    public function testWithAddedHeaderChangesTheHeaders(): void
    {
        $response = Response::fromResponse(new RequestsResponse());

        $response = $response->withAddedHeader('Name', 'value');

        TestCase::assertSame(['Name' => ['value']], $response->getHeaders());
    }

    /**
     * Tests changing the header when using withAddedHeader().
     *
     * @covers \Art4\Requests\Psr\Response::withAddedHeader
     */
    public function testWithAddedHeaderCaseInsensitiveChangesTheHeaders(): void
    {
        $response = Response::fromResponse(new RequestsResponse());

        $response = $response->withAddedHeader('name', 'value1');
        $response = $response->withAddedHeader('NAME', 'value2');

        TestCase::assertSame(['NAME' => ['value1', 'value2']], $response->getHeaders());
    }
}
