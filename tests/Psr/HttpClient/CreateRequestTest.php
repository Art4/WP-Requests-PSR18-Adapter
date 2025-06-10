<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\HttpClient;

use Art4\Requests\Psr\HttpClient;
use Art4\Requests\Tests\TypeProviderHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use WpOrg\Requests\Exception\InvalidArgument;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class CreateRequestTest extends TestCase
{
    /**
     * Tests receiving an Request when using createRequest().
     *
     * @covers \Art4\Requests\Psr\HttpClient::createRequest
     *
     * @return void
     */
    public function testCreateRequestWithUriInstanceReturnsRequest()
    {
        $httpClient = new HttpClient([]);

        $uri = $this->createMock(UriInterface::class);

        TestCase::assertInstanceOf(
            RequestInterface::class,
            $httpClient->createRequest('', $uri)
        );
    }

    /**
     * Tests receiving an Request when using createRequest().
     *
     * @covers \Art4\Requests\Psr\HttpClient::createRequest
     *
     * @return void
     */
    public function testCreateRequestWithUriStringReturnsRequest()
    {
        $httpClient = new HttpClient([]);

        $uri = 'https://example.org';

        TestCase::assertInstanceOf(
            RequestInterface::class,
            $httpClient->createRequest('', $uri)
        );
    }

    /**
     * Tests receiving an exception when the createRequest() method received an invalid input type as `$uri`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\HttpClient::createRequest
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    #[DataProvider('dataInvalidTypeNotString')]
    public function testCreateRequestWithoutUriStringThrowsException($input)
    {
        $httpClient = new HttpClient([]);

        $this->expectException(InvalidArgument::class);
        $this->expectExceptionMessage(sprintf(
            '%s::createRequest(): Argument #2 ($uri) must be of type %s|string',
            HttpClient::class,
            UriInterface::class
        ));

        $httpClient->createRequest('', $input);
    }

    /**
     * Data Provider.
     *
     * @return array<string, mixed>
     */
    public static function dataInvalidTypeNotString()
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_STRING);
    }
}
