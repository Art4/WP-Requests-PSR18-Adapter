<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Integration;

use Art4\Requests\Psr\HttpClient;
use GuzzleHttp\Psr7\HttpFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use WpOrg\Requests\Transport\Curl;
use WpOrg\Requests\Transport\Fsockopen;

/**
 * Tests inspired by symfony/contracts HttpClientTestCase, ported to PSR-18.
 *
 * Uses the vendored Symfony test server router (tests/Integration/symfony-server/index.php).
 * Server is started once per class in setUpBeforeClass and torn down in tearDownAfterClass.
 *
 * @group integration
 */
final class SymfonyHttpClientIntegrationTest extends TestCase
{
    private const PORT = 8057;
    private const HOST = '127.0.0.1';

    /** @var resource|null */
    private static $serverProcess;

    private static RequestFactoryInterface $requestFactory;
    private static StreamFactoryInterface $streamFactory;

    public static function setUpBeforeClass(): void
    {
        self::$requestFactory = self::$streamFactory = new HttpFactory();
        self::startServer();
    }

    public static function tearDownAfterClass(): void
    {
        self::stopServer();
    }

    private static function startServer(): void
    {
        $docroot = __DIR__ . '/symfony-server';
        $cmd = sprintf(
            'php -dopcache.enable=0 -dvariables_order=EGPCS -S %s:%d -t %s',
            self::HOST,
            self::PORT,
            escapeshellarg($docroot)
        );

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['file', '/tmp/symfony-test-server.log', 'a'],
            2 => ['file', '/tmp/symfony-test-server.log', 'a'],
        ];
        self::$serverProcess = proc_open($cmd, $descriptors, $pipes);
        fclose($pipes[0]);

        // Wait for server to accept connections (up to 5s)
        $start = microtime(true);
        while (microtime(true) - $start < 5.0) {
            $sock = @fsockopen(self::HOST, self::PORT, $errno, $errstr, 0.2);
            if ($sock) {
                fclose($sock);
                return;
            }
            usleep(50000);
        }
        throw new \RuntimeException("Symfony test server failed to start on " . self::HOST . ":" . self::PORT);
    }

    private static function stopServer(): void
    {
        if (is_resource(self::$serverProcess)) {
            $status = proc_get_status(self::$serverProcess);
            if ($status['running'] ?? false) {
                // SIGTERM — proc_terminate uses SIGTERM by default
                proc_terminate(self::$serverProcess);
            }
            proc_close(self::$serverProcess);
        }
        self::$serverProcess = null;
    }

    private function client(array $options = []): HttpClient
    {
        $transport = (getenv('REQUESTS_TRANSPORT') ?: 'curl') === 'fsockopen'
            ? Fsockopen::class
            : Curl::class;

        return new HttpClient(array_merge([
            'timeout' => 5,
            'connect_timeout' => 5,
            'transport' => $transport,
        ], $options));
    }

    private function url(string $path): string
    {
        return 'http://' . self::HOST . ':' . self::PORT . $path;
    }

    // -----------------------------------------------------------------------
    // Tests
    // -----------------------------------------------------------------------

    /**
     * Symfony equivalent: HttpClientTestCase::testDnsError
     *
     * PSR-18 §1: "If the request cannot be sent due to a network failure of any
     * kind, including a timeout, a NetworkExceptionInterface MUST be thrown."
     *
     * DNS resolution failure is the canonical network error.
     */
    public function testDnsError(): void
    {
        $request = self::$requestFactory->createRequest('GET', 'http://does.not.exist.symfony.test/');

        $this->expectException(NetworkExceptionInterface::class);
        $this->client()->sendRequest($request);
    }

    /**
     * Symfony equivalent: HttpClientTestCase::testConnectTimeout / testTimeoutOnAccess
     *
     * Connecting to a closed port should produce a NetworkException, not a
     * RequestException.
     */
    public function testConnectionRefused(): void
    {
        // Port 1 is reserved (TCPMUX) and almost certainly not listening
        $request = self::$requestFactory->createRequest('GET', 'http://127.0.0.1:1/');

        $this->expectException(NetworkExceptionInterface::class);
        $this->client(['connect_timeout' => 2])->sendRequest($request);
    }

    /**
     * Symfony equivalent: HttpClientTestCase::testHeadRequest
     *
     * HEAD to /head returns a body-less response with X-Request-Vars header
     * containing a JSON dump of the SERVER vars.
     */
    public function testHeadRequest(): void
    {
        $request = self::$requestFactory->createRequest('HEAD', $this->url('/head'));

        $response = $this->client()->sendRequest($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getHeaderLine('X-Request-Vars'));
        $vars = json_decode($response->getHeaderLine('X-Request-Vars'), true);
        $this->assertSame('HEAD', $vars['REQUEST_METHOD']);
        // Body must be empty for HEAD
        $this->assertSame('', $response->getBody()->__toString());
    }

    /**
     * Symfony equivalent: HttpClientTestCase::testRedirectAfterPost
     *
     * POST to /302 with a body should follow the redirect, switch to GET, and
     * drop the body / Content-Length / Content-Type. Final response from / is
     * the JSON dump of the GET request vars (no body sent).
     */
    public function testRedirectAfterPost(): void
    {
        $body = self::$streamFactory->createStream('foo=bar');
        $request = self::$requestFactory->createRequest('POST', $this->url('/302'))
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withBody($body);

        $response = $this->client(['follow_redirects' => true])->sendRequest($request);

        $this->assertSame(200, $response->getStatusCode());
        $vars = json_decode($response->getBody()->__toString(), true);
        $this->assertSame('GET', $vars['REQUEST_METHOD'], 'Method should switch to GET after 302');
    }

    /**
     * Symfony equivalent: HttpClientTestCase::testRedirect307
     *
     * 307 must preserve the original method and body. POST to /307 follows
     * to /post, which still receives a POST.
     */
    public function testRedirect307KeepsPostMethod(): void
    {
        $body = self::$streamFactory->createStream('{"foo":"bar"}');
        $request = self::$requestFactory->createRequest('POST', $this->url('/307'))
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $response = $this->client(['follow_redirects' => true])->sendRequest($request);

        $this->assertSame(200, $response->getStatusCode());
        $payload = json_decode($response->getBody()->__toString(), true);
        $this->assertSame('POST', $payload['REQUEST_METHOD'], '307 must preserve POST method');
    }

    /**
     * Symfony equivalent: HttpClientTestCase::testRelativeRedirect
     *
     * Server responds with `Location: ..` (relative). Adapter should resolve
     * it against the request URL.
     */
    public function testRelativeRedirect(): void
    {
        $request = self::$requestFactory->createRequest('GET', $this->url('/302/relative'));

        $response = $this->client(['follow_redirects' => true])->sendRequest($request);

        $this->assertSame(200, $response->getStatusCode());
        // Final URL after `..` resolution should be /
        $vars = json_decode($response->getBody()->__toString(), true);
        $this->assertSame('/', $vars['REQUEST_URI'] ?? null);
    }

    /**
     * Symfony equivalent: HttpClientTestCase::testChunkedEncoding
     *
     * Server sends a chunked-encoded body. Adapter must transparently
     * dechunk it.
     */
    public function testChunkedEncoding(): void
    {
        $request = self::$requestFactory->createRequest('GET', $this->url('/chunked'));

        $response = $this->client()->sendRequest($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Symfony is awesome!', $response->getBody()->__toString());
    }

    /**
     * Symfony equivalent: HttpClientTestCase::testGzipBroken
     *
     * Server claims Content-Encoding: gzip but sends plain text. Adapter
     * should either fail cleanly (RequestException) or surface the broken
     * payload — but must not crash or hang.
     */
    public function testGzipBroken(): void
    {
        $request = self::$requestFactory->createRequest('GET', $this->url('/gzip-broken'));

        try {
            $response = $this->client()->sendRequest($request);
            // If no exception, body should have been delivered (possibly malformed)
            $this->assertSame(200, $response->getStatusCode());
        } catch (\Psr\Http\Client\ClientExceptionInterface $e) {
            // Equally acceptable: surface as a client exception
            $this->assertNotEmpty($e->getMessage());
        }
    }

    /**
     * Probes the cURL+HEAD+body hang documented in
     * docs/head-body-hang-2026-04-16.md. With the patched adapter
     * (data_format='body' workaround) the body is forwarded to the transport;
     * Curl's transport then drops it but keeps Content-Length, server hangs.
     */
    public function testHeadRequestWithBody(): void
    {
        $body = self::$streamFactory->createStream('arbitrary-body-bytes');
        $request = self::$requestFactory->createRequest('HEAD', $this->url('/head'))
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withBody($body);

        $response = $this->client(['timeout' => 3])->sendRequest($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('', $response->getBody()->__toString());
    }

    /**
     * Symfony equivalent: HttpClientTestCase::testMaxRedirects
     *
     * Default redirect cap should kick in. /302 redirects to /, which 200s,
     * so a single redirect should still succeed when redirects=1 is allowed.
     * To exhaust the cap, we'd need a redirect loop; instead we set
     * redirects=0 and expect the adapter to surface a "too many redirects"
     * style failure (or expose the 302 directly).
     */
    public function testMaxRedirectsZero(): void
    {
        $request = self::$requestFactory->createRequest('GET', $this->url('/302'));

        try {
            $response = $this->client(['follow_redirects' => true, 'redirects' => 0])->sendRequest($request);
            // If the adapter surfaces the 302 itself instead of throwing, that's
            // also a reasonable interpretation of "redirects=0".
            $this->assertSame(302, $response->getStatusCode());
        } catch (\Psr\Http\Client\ClientExceptionInterface $e) {
            $this->assertStringContainsStringIgnoringCase('redirect', $e->getMessage());
        }
    }

    /**
     * Symfony equivalent: HttpClientTestCase::testInvalidRedirect
     *
     * Server responds with `Location: //?foo=bar` (schema-relative). The
     * adapter / underlying Requests should resolve this against the request
     * URL and follow it, ending up back at the test server root.
     */
    public function testSchemaRelativeRedirect(): void
    {
        $request = self::$requestFactory->createRequest('GET', $this->url('/301/invalid'));

        $response = $this->client(['follow_redirects' => true])->sendRequest($request);

        // The redirect target resolves to a URL on the same authority. Either
        // it's followed cleanly (200) or it surfaces as a parse error somewhere.
        // We just want to make sure the adapter doesn't crash.
        $this->assertContains($response->getStatusCode(), [200, 301, 302, 400]);
    }

    /**
     * Server's /301/bad-tld responds with `Location: http://foo.example.`
     * Following the redirect should produce a NetworkException (DNS will
     * fail on the target).
     */
    public function testRedirectToBadTldSurfacesNetworkException(): void
    {
        $request = self::$requestFactory->createRequest('GET', $this->url('/301/bad-tld'));

        $this->expectException(NetworkExceptionInterface::class);
        $this->client(['follow_redirects' => true])->sendRequest($request);
    }

    /**
     * Basic 404 handling — server returns 404 with empty body.
     * Adapter must not throw; PSR-18 only throws for transport / protocol
     * errors, not HTTP error status codes.
     */
    public function test404(): void
    {
        $request = self::$requestFactory->createRequest('GET', $this->url('/404'));

        $response = $this->client()->sendRequest($request);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
    }

    /**
     * POST with JSON body. Server's /post endpoint decodes the body as JSON,
     * merges with REQUEST_METHOD, and echoes back as JSON.
     */
    public function testPostJson(): void
    {
        $payload = ['hello' => 'world', 'n' => 42];
        $body = self::$streamFactory->createStream(json_encode($payload));
        $request = self::$requestFactory->createRequest('POST', $this->url('/post'))
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $response = $this->client()->sendRequest($request);

        $this->assertSame(200, $response->getStatusCode());
        $decoded = json_decode($response->getBody()->__toString(), true);
        $this->assertSame('POST', $decoded['REQUEST_METHOD']);
        $this->assertSame('world', $decoded['hello']);
        $this->assertSame(42, $decoded['n']);
    }

    /**
     * Symfony equivalent: HttpClientTestCase::testLengthBroken
     *
     * Server sends `Content-Length: 1000` but no body. Adapter behavior
     * depends on transport — should either throw or surface a short body,
     * but must not hang past the timeout.
     */
    public function testLengthBroken(): void
    {
        $request = self::$requestFactory->createRequest('GET', $this->url('/length-broken'));
        $start = microtime(true);

        try {
            $response = $this->client(['timeout' => 3])->sendRequest($request);
            $this->assertSame(200, $response->getStatusCode());
        } catch (\Psr\Http\Client\ClientExceptionInterface $e) {
            $this->assertNotEmpty($e->getMessage());
        }

        // Whatever the outcome, must not block past timeout
        $elapsed = microtime(true) - $start;
        $this->assertLessThan(4.0, $elapsed, 'Request must not hang past timeout');
    }

    /**
     * Symfony equivalent: HttpClientTestCase::testTimeoutOnHeader
     *
     * Server sleeps 300ms before responding. With a 5s timeout, the request
     * should succeed cleanly.
     */
    public function testTimeoutHeaderUnderLimit(): void
    {
        $request = self::$requestFactory->createRequest('GET', $this->url('/timeout-header'));

        $response = $this->client(['timeout' => 5])->sendRequest($request);

        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * Server sleeps 300ms before responding. With a 100ms timeout, a
     * NetworkException must be thrown (timeout is a network failure per
     * PSR-18 §1).
     */
    public function testTimeoutThrowsNetworkException(): void
    {
        $request = self::$requestFactory->createRequest('GET', $this->url('/timeout-header'));

        $this->expectException(NetworkExceptionInterface::class);
        // 0.1s = 100ms: server sleeps 300ms, so this must time out
        $this->client(['timeout' => 0.1, 'connect_timeout' => 0.1])->sendRequest($request);
    }
}
