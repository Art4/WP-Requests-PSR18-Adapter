<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Art4\Requests\Psr\Response;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class WithStatusTest extends TestCase
{
    /**
     * Tests changing the status code when using withStatus().
     *
     * @covers \Art4\Requests\Psr\Response::withStatus
     *
     * @return void
     */
    public function testWithStatusReturnsResponseInstance()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->assertInstanceOf(ResponseInterface::class, $response->withStatus(200));
    }

    /**
     * Tests changing the status code when using withStatus().
     *
     * @covers \Art4\Requests\Psr\Response::withStatus
     *
     * @return void
     */
    public function testWithStatusReturnsNewInstance()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->assertNotSame($response, $response->withStatus(200));
    }

    /**
     * Data Provider.
     *
     * @return array<string, mixed>
     */
    public function dataInvalidTypeNotInteger()
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_INT);
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
     * Tests receiving an exception when the withStatus() method received an invalid input type as `$reasonPhrase`.
     *
     * @dataProvider dataWithStatus
     *
     * @covers \Art4\Requests\Psr\Response::withStatus
     *
     * @return void
     */
    public function testWithStatusChangesStatusCode(int $code, string $phrase, string $expected)
    {
        $response = Response::fromResponse(new RequestsResponse());

        $response = $response->withStatus($code, $phrase);

        $this->assertSame($code, $response->getStatusCode());
        $this->assertSame($expected, $response->getReasonPhrase());
    }

    /**
     * Data Provider.
     *
     * @return array<string,array<int|string>>
     */
    public static function dataWithStatus(): array
    {
        return [
            'Return an instance with the specified status code and, optionally, reason phrase.' => [200, 'foobar', 'foobar'],
            'If no reason phrase is specified, implementations MAY choose to default to the RFC 7231 or IANA recommended reason phrase' => [200, '', 'OK'],
        ];
    }
}
