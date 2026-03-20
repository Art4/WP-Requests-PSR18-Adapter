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
        $transport = $this->createMock(Transport::class);
        $transport->expects(TestCase::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            TestCase::assertSame('https://example.org/', $url);
            TestCase::assertSame([], $data);
            TestCase::assertSame('GET', $options['type']);

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

        TestCase::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that GET requests with "[]" body send empty array.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testGetRequestWithBracketBodySendsEmptyArray(): void
    {
        $transport = $this->createMock(Transport::class);
        $transport->expects(TestCase::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            TestCase::assertSame('https://example.org/', $url);
            TestCase::assertSame([], $data);
            TestCase::assertSame('GET', $options['type']);

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

        TestCase::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that HEAD requests with body send empty array.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testHeadRequestWithBodySendsEmptyArray(): void
    {
        $transport = $this->createMock(Transport::class);
        $transport->expects(TestCase::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            TestCase::assertSame('https://example.org/', $url);
            TestCase::assertSame([], $data);
            TestCase::assertSame('HEAD', $options['type']);

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

        TestCase::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that DELETE requests with body send empty array.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testDeleteRequestWithBodySendsEmptyArray(): void
    {
        $transport = $this->createMock(Transport::class);
        $transport->expects(TestCase::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            TestCase::assertSame('https://example.org/posts/1', $url);
            TestCase::assertSame([], $data);
            TestCase::assertSame('DELETE', $options['type']);

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

        TestCase::assertSame(204, $response->getStatusCode());
    }

    /**
     * Tests that POST requests with JSON body send body as string.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPostRequestWithJsonBodySendsString(): void
    {
        $transport = $this->createMock(Transport::class);
        $transport->expects(TestCase::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            TestCase::assertSame('https://example.org/posts', $url);
            TestCase::assertSame('{"title":"Test Post","body":"Content"}', $data);
            TestCase::assertSame('POST', $options['type']);

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

        TestCase::assertSame(201, $response->getStatusCode());
    }

    /**
     * Tests that POST requests with empty body send empty array.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPostRequestWithEmptyBodySendsEmptyArray(): void
    {
        $transport = $this->createMock(Transport::class);
        $transport->expects(TestCase::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            TestCase::assertSame('https://example.org/posts', $url);
            TestCase::assertSame([], $data);
            TestCase::assertSame('POST', $options['type']);

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

        TestCase::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that POST requests with form-urlencoded body send parsed array.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPostRequestWithFormUrlencodedBodySendsArray(): void
    {
        $transport = $this->createMock(Transport::class);
        $transport->expects(TestCase::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            TestCase::assertSame('https://example.org/form', $url);
            TestCase::assertSame(['name' => 'John Doe', 'email' => 'john@example.com'], $data);
            TestCase::assertSame('POST', $options['type']);

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

        TestCase::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that POST requests with form-urlencoded charset body send parsed array.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPostRequestWithFormUrlencodedCharsetBodySendsArray(): void
    {
        $transport = $this->createMock(Transport::class);
        $transport->expects(TestCase::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            TestCase::assertSame('https://example.org/form', $url);
            TestCase::assertSame(['username' => 'admin', 'password' => 'secret'], $data);
            TestCase::assertSame('POST', $options['type']);

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

        TestCase::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that PUT requests with JSON body send body as string.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPutRequestWithJsonBodySendsString(): void
    {
        $transport = $this->createMock(Transport::class);
        $transport->expects(TestCase::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            TestCase::assertSame('https://example.org/posts/1', $url);
            TestCase::assertSame('{"title":"Updated Post"}', $data);
            TestCase::assertSame('PUT', $options['type']);

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

        TestCase::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that PATCH requests with JSON body send body as string.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPatchRequestWithJsonBodySendsString(): void
    {
        $transport = $this->createMock(Transport::class);
        $transport->expects(TestCase::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            TestCase::assertSame('https://example.org/posts/1', $url);
            TestCase::assertSame('{"title":"Patched Title"}', $data);
            TestCase::assertSame('PATCH', $options['type']);

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

        TestCase::assertSame(200, $response->getStatusCode());
    }

    /**
     * Tests that POST requests with XML body send body as string.
     *
     * @covers \Art4\Requests\Psr\HttpClient::sendRequest
     * @covers \Art4\Requests\Psr\HttpClient::prepareRequestData
     */
    public function testPostRequestWithXmlBodySendsString(): void
    {
        $transport = $this->createMock(Transport::class);
        $transport->expects(TestCase::once())->method('request')->willReturnCallback(function ($url, $headers, $data, array $options): string {
            TestCase::assertSame('https://example.org/api', $url);
            TestCase::assertSame('<?xml version="1.0"?><root><item>value</item></root>', $data);
            TestCase::assertSame('POST', $options['type']);

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

        TestCase::assertSame(200, $response->getStatusCode());
    }
}
