<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use Art4\Requests\Psr\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Http\Message\ResponseInterface;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithStatusTest extends TestCase
{
    /**
     * Tests changing the status code when using withStatus().
     *
     * @covers \Art4\Requests\Psr\Response::withStatus
     */
    public function testWithStatusReturnsResponseInstance(): void
    {
        $response = Response::fromResponse(new RequestsResponse());

        TestCase::assertInstanceOf(ResponseInterface::class, $response->withStatus(200));
    }

    /**
     * Tests changing the status code when using withStatus().
     *
     * @covers \Art4\Requests\Psr\Response::withStatus
     */
    public function testWithStatusReturnsNewInstance(): void
    {
        $response = Response::fromResponse(new RequestsResponse());

        TestCase::assertNotSame($response, $response->withStatus(200));
    }

    /**
     * Tests receiving an exception when the withStatus() method received an invalid input type as `$reasonPhrase`.
     *
     * @covers \Art4\Requests\Psr\Response::withStatus
     */
    public function testWithStatusWithInvalidCodeThrowsException(): void
    {
        $response = Response::fromResponse(new RequestsResponse());

        TestCase::expectException(\InvalidArgumentException::class);
        TestCase::expectExceptionMessage('Invalid status code `0`');

        $response->withStatus(0);
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

    /**
     * Tests getting new status code and reason when using withStatus() method.
     *
     * @dataProvider dataWithStatus
     *
     * @covers \Art4\Requests\Psr\Response::withStatus
     * @covers \Art4\Requests\Psr\Response::getReasonPhrase
     */
    #[DataProvider('dataWithStatus')]
    public function testWithStatusChangesStatusCode(int $code, string $phrase, string $expected): void
    {
        $response = Response::fromResponse(new RequestsResponse());

        $response = $response->withStatus($code, $phrase);

        TestCase::assertSame($code, $response->getStatusCode());
        TestCase::assertSame($expected, $response->getReasonPhrase());
    }
}
