<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\HttpClient;

use Art4\Requests\Psr\HttpClient;
use WpOrg\Requests\Transport;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class PrepareRequestDataTest extends TestCase
{
    /**
     * Tests that GET requests with empty body send empty array.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testGetRequestWithEmptyBodySendsEmptyArray(): void
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
                'success';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('GET', 'https://example.org');

        $response = $httpClient->sendRequest($request);

        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that GET requests with "[]" body send empty array.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testGetRequestWithBracketBodySendsEmptyArray(): void
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
                'success';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('GET', 'https://example.org');
        $request = $request->withBody($httpClient->createStream('[]'));

        $response = $httpClient->sendRequest($request);

        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that HEAD requests with body pass body as string with data_format='body'.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testHeadRequestWithBodyPassesBodyAsString(): void
    {
        $transport = self::createMock(Transport::class);
        $transport->expects(self::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            self::assertSame('https://example.org/', $url);
            self::assertSame(['Host' => 'example.org'], $headers);
            self::assertSame('some body', $data);
            self::assertSame('HEAD', $options['type']);
            self::assertSame('body', $options['data_format']);

            return
                'HTTP/1.1 200 OK' . "\r\n" .
                'Content-Type:text/plain' . "\r\n" .
                "\r\n";
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('HEAD', 'https://example.org');
        $request = $request->withBody($httpClient->createStream('some body'));

        $response = $httpClient->sendRequest($request);

        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that DELETE requests with body pass body as string with data_format='body'.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testDeleteRequestWithBodyPassesBodyAsString(): void
    {
        $transport = self::createMock(Transport::class);
        $transport->expects(self::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            self::assertSame('https://example.org/posts/1', $url);
            self::assertSame(['Host' => 'example.org'], $headers);
            self::assertSame('ignored', $data);
            self::assertSame('DELETE', $options['type']);
            self::assertSame('body', $options['data_format']);

            return
                'HTTP/1.1 204 No Content' . "\r\n" .
                "\r\n";
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('DELETE', 'https://example.org/posts/1');
        $request = $request->withBody($httpClient->createStream('ignored'));

        $response = $httpClient->sendRequest($request);

        self::assertSame(204, $response->getStatusCode());
    }

    /**
     * Tests that POST requests with JSON body send body as string.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPostRequestWithJsonBodySendsString(): void
    {
        $transport = self::createMock(Transport::class);
        $transport->expects(self::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            self::assertSame('https://example.org/posts', $url);
            self::assertSame(['Host' => 'example.org', 'Content-Type' => 'application/json'], $headers);
            self::assertSame('{"title":"Test Post","body":"Content"}', $data);
            self::assertSame('POST', $options['type']);

            return
                'HTTP/1.1 201 Created' . "\r\n" .
                'Content-Type:application/json' . "\r\n" .
                "\r\n" .
                '{"id":1,"title":"Test Post"}';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('POST', 'https://example.org/posts');
        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withBody($httpClient->createStream('{"title":"Test Post","body":"Content"}'));

        $response = $httpClient->sendRequest($request);

        self::assertSame(201, $response->getStatusCode());
    }

    /**
     * Tests that POST requests with JSON body send body as string.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPostRequestWithoutBodyAndContentLengthAndTransferEncodingHeadersRemovesHeaders(): void
    {
        $transport = self::createMock(Transport::class);
        $transport->expects(self::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            self::assertSame('https://example.org/posts', $url);
            self::assertSame(['Host' => 'example.org', 'Content-Type' => 'application/json'], $headers);
            self::assertSame([], $data);
            self::assertSame('POST', $options['type']);

            return
                'HTTP/1.1 201 Created' . "\r\n" .
                'Content-Type:application/json' . "\r\n" .
                "\r\n" .
                '{"id":1,"title":"Test Post"}';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('POST', 'https://example.org/posts');
        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withAddedHeader('Content-Length', '99999');
        $request = $request->withAddedHeader('Transfer-Encoding', 'UTF-8');
        $request = $request->withBody($httpClient->createStream(''));

        $response = $httpClient->sendRequest($request);

        self::assertSame(201, $response->getStatusCode());
    }

    /**
     * Tests that POST requests with empty body send empty array.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPostRequestWithEmptyBodySendsEmptyArray(): void
    {
        $transport = self::createMock(Transport::class);
        $transport->expects(self::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            self::assertSame('https://example.org/posts', $url);
            self::assertSame(['Host' => 'example.org'], $headers);
            self::assertSame([], $data);
            self::assertSame('POST', $options['type']);

            return
                'HTTP/1.1 200 OK' . "\r\n" .
                'Content-Type:text/plain' . "\r\n" .
                "\r\n" .
                'success';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('POST', 'https://example.org/posts');

        $response = $httpClient->sendRequest($request);

        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that POST requests with form-urlencoded body send body as string.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPostRequestWithFormUrlencodedBodySendsString(): void
    {
        $transport = self::createMock(Transport::class);
        $transport->expects(self::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            self::assertSame('https://example.org/form', $url);
            self::assertSame(['Host' => 'example.org', 'Content-Type' => 'application/x-www-form-urlencoded'], $headers);
            self::assertSame('name=John+Doe&email=john%40example.com', $data);
            self::assertSame('POST', $options['type']);

            return
                'HTTP/1.1 200 OK' . "\r\n" .
                'Content-Type:text/plain' . "\r\n" .
                "\r\n" .
                'Form submitted';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('POST', 'https://example.org/form');
        $request = $request->withHeader('Content-Type', 'application/x-www-form-urlencoded');
        $request = $request->withBody($httpClient->createStream('name=John+Doe&email=john%40example.com'));

        $response = $httpClient->sendRequest($request);

        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that POST requests with form-urlencoded charset body send body as string.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPostRequestWithFormUrlencodedCharsetBodySendsString(): void
    {
        $transport = self::createMock(Transport::class);
        $transport->expects(self::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            self::assertSame('https://example.org/form', $url);
            self::assertSame(['Host' => 'example.org', 'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'], $headers);
            self::assertSame('username=admin&password=secret', $data);
            self::assertSame('POST', $options['type']);

            return
                'HTTP/1.1 200 OK' . "\r\n" .
                'Content-Type:text/plain' . "\r\n" .
                "\r\n" .
                'Login successful';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('POST', 'https://example.org/form');
        $request = $request->withHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        $request = $request->withBody($httpClient->createStream('username=admin&password=secret'));

        $response = $httpClient->sendRequest($request);

        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that POST requests with repeated form-urlencoded params are preserved.
     *
     * Some APIs use repeated parameter names (e.g. item=foo&item=bar) to send
     * arrays. parse_str() would collapse these into a single value, so the
     * body must be passed as a raw string.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPostRequestWithRepeatedFormParamsPreservesAll(): void
    {
        $body = 'item=first&item=second&item=third&category=test';

        $transport = self::createMock(Transport::class);
        $transport->expects(self::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options) use ($body): string {
            self::assertSame('https://example.org/batch', $url);
            self::assertSame(['Host' => 'example.org', 'Content-Type' => 'application/x-www-form-urlencoded'], $headers);
            self::assertSame($body, $data);
            self::assertSame('POST', $options['type']);

            return
                'HTTP/1.1 200 OK' . "\r\n" .
                'Content-Type:application/json' . "\r\n" .
                "\r\n" .
                '{"processed":3}';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('POST', 'https://example.org/batch');
        $request = $request->withHeader('Content-Type', 'application/x-www-form-urlencoded');
        $request = $request->withBody($httpClient->createStream($body));

        $response = $httpClient->sendRequest($request);

        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that PUT requests with JSON body send body as string.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPutRequestWithJsonBodySendsString(): void
    {
        $transport = self::createMock(Transport::class);
        $transport->expects(self::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            self::assertSame('https://example.org/posts/1', $url);
            self::assertSame(['Host' => 'example.org', 'Content-Type' => 'application/json'], $headers);
            self::assertSame('{"title":"Updated Post"}', $data);
            self::assertSame('PUT', $options['type']);

            return
                'HTTP/1.1 200 OK' . "\r\n" .
                'Content-Type:application/json' . "\r\n" .
                "\r\n" .
                '{"id":1,"title":"Updated Post"}';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('PUT', 'https://example.org/posts/1');
        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withBody($httpClient->createStream('{"title":"Updated Post"}'));

        $response = $httpClient->sendRequest($request);

        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that PATCH requests with JSON body send body as string.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPatchRequestWithJsonBodySendsString(): void
    {
        $transport = self::createMock(Transport::class);
        $transport->expects(self::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            self::assertSame('https://example.org/posts/1', $url);
            self::assertSame(['Host' => 'example.org', 'Content-Type' => 'application/json'], $headers);
            self::assertSame('{"title":"Patched Title"}', $data);
            self::assertSame('PATCH', $options['type']);

            return
                'HTTP/1.1 200 OK' . "\r\n" .
                'Content-Type:application/json' . "\r\n" .
                "\r\n" .
                '{"id":1,"title":"Patched Title"}';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('PATCH', 'https://example.org/posts/1');
        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withBody($httpClient->createStream('{"title":"Patched Title"}'));

        $response = $httpClient->sendRequest($request);

        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that POST requests with XML body send body as string.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPostRequestWithXmlBodySendsString(): void
    {
        $transport = self::createMock(Transport::class);
        $transport->expects(self::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            self::assertSame('https://example.org/api', $url);
            self::assertSame(['Host' => 'example.org', 'Content-Type' => 'application/xml'], $headers);
            self::assertSame('<?xml version="1.0"?><root><item>value</item></root>', $data);
            self::assertSame('POST', $options['type']);

            return
                'HTTP/1.1 200 OK' . "\r\n" .
                'Content-Type:application/xml' . "\r\n" .
                "\r\n" .
                '<?xml version="1.0"?><response>OK</response>';
        });

        $httpClient = new HttpClient([
            'transport' => $transport,
        ]);

        $request = $httpClient->createRequest('POST', 'https://example.org/api');
        $request = $request->withHeader('Content-Type', 'application/xml');
        $request = $request->withBody($httpClient->createStream('<?xml version="1.0"?><root><item>value</item></root>'));

        $response = $httpClient->sendRequest($request);

        self::assertSame(200, $response->getStatusCode());
    }
}
