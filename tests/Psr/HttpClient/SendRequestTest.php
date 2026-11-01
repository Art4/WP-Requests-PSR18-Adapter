<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\HttpClient;

use Art4\Requests\Exception\Psr\NetworkException;
use Art4\Requests\Exception\Psr\RequestException;
use Art4\Requests\Psr\HttpClient;
use Art4\Requests\Psr\Response;
use Psr\Http\Message\ResponseInterface;
use WpOrg\Requests\Exception\Transport as ExceptionTransport;
use WpOrg\Requests\Transport;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class SendRequestTest extends TestCase
{
    /**
     * Tests receiving a response when using sendRequest().
     *
     * @covers \Art4\Requests\Psr\HttpClient::__construct
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     */
    public function testSendRequestWithGetSendsCorrectDataAndReturnsCorrectResponseData(): void
    {
        $transport = self::createMock(Transport::class);
        $transport->expects(self::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            self::assertSame('https://example.org/', $url);
            self::assertSame(['Host' => 'example.org'], $headers);
            self::assertSame([], $data);
            self::assertSame('GET', $options['type']);

            return
                'HTTP/1.1 200 OK' . "\r\n" .
                'Content-Type:text/plain' . "\r\n" .
                "\r\n" .
                'foobar';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('GET', 'https://example.org');

        $response = $httpClient->sendRequest($request);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('OK', $response->getReasonPhrase());
        self::assertSame('1.1', $response->getProtocolVersion());
        self::assertSame(['content-type' => ['text/plain']], $response->getHeaders());
        self::assertSame('foobar', $response->getBody()->__toString());
    }

    /**
     * Tests receiving a response when using sendRequest().
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     */
    public function testSendRequestWithPostSendsCorrectDataAndReturnsCorrectResponseData(): void
    {
        $transport = self::createMock(Transport::class);
        $transport->expects(self::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            self::assertSame('https://example.org/posts', $url);
            self::assertSame(['Host' => 'example.org'], $headers);
            self::assertSame('{"title":"Post title"}', $data);
            self::assertSame('POST', $options['type']);

            return
                'HTTP/1.1 201 Created' . "\r\n" .
                'Content-Type:application/json' . "\r\n" .
                "\r\n" .
                '{"id":1,"title":"Post title"}';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('POST', 'https://example.org/posts');
        $request = $request->withBody($httpClient->createStream('{"title":"Post title"}'));

        $response = $httpClient->sendRequest($request);

        self::assertSame(201, $response->getStatusCode());
        self::assertSame('Created', $response->getReasonPhrase());
        self::assertSame('1.1', $response->getProtocolVersion());
        self::assertSame(['content-type' => ['application/json']], $response->getHeaders());
        self::assertSame('{"id":1,"title":"Post title"}', $response->getBody()->__toString());
    }

    /**
     * Tests receiving a response when using sendRequest().
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     */
    public function testSendRequestReturnsResponseOn404Error(): void
    {
        $transport = self::createMock(Transport::class);
        $transport->expects(self::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            self::assertSame('https://example.org/not-found', $url);
            self::assertSame(['Host' => 'example.org'], $headers);
            self::assertSame([], $data);
            self::assertSame('GET', $options['type']);

            return
                'HTTP/1.1 404 Not Found' . "\r\n" .
                'Content-Type:text/plain' . "\r\n" .
                "\r\n" .
                '404 Not Found';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('GET', 'https://example.org/not-found');

        $response = $httpClient->sendRequest($request);

        self::assertSame(404, $response->getStatusCode());
        self::assertSame('Not Found', $response->getReasonPhrase());
        self::assertSame('1.1', $response->getProtocolVersion());
        self::assertSame(['content-type' => ['text/plain']], $response->getHeaders());
        self::assertSame('404 Not Found', $response->getBody()->__toString());
    }

    /**
     * Tests receiving a response when using sendRequest().
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     */
    public function testSendRequestReturnsResponseOn503Error(): void
    {
        $transport = self::createMock(Transport::class);
        $transport->expects(self::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            self::assertSame('https://example.org/not-available', $url);
            self::assertSame(['Host' => 'example.org'], $headers);
            self::assertSame([], $data);
            self::assertSame('GET', $options['type']);

            return
                'HTTP/1.1 503 Service Unavailable' . "\r\n" .
                'Content-Type:text/plain' . "\r\n" .
                "\r\n" .
                '503 Service Unavailable';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('GET', 'https://example.org/not-available');

        $response = $httpClient->sendRequest($request);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame(503, $response->getStatusCode());
        self::assertSame('Service Unavailable', $response->getReasonPhrase());
        self::assertSame('1.1', $response->getProtocolVersion());
        self::assertSame(['content-type' => ['text/plain']], $response->getHeaders());
        self::assertSame('503 Service Unavailable', $response->getBody()->__toString());
    }

    /**
     * Tests receiving an exception when using sendRequest().
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     */
    public function testSendRequestThrowsRequestException(): void
    {
        $transport = self::createStub(Transport::class);

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('GET', '');

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Only HTTP(S) requests are handled.');

        $httpClient->sendRequest($request);
    }

    /**
     * Tests receiving an exception when using sendRequest().
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     */
    public function testSendRequestThrowsNetworkException(): void
    {
        $e = new ExceptionTransport('error message', 'Unknown');

        $transport = self::createStub(Transport::class);
        $transport->method('request')->willThrowException($e);

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('GET', 'https://example.org');

        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('error message');

        $httpClient->sendRequest($request);
    }
}
