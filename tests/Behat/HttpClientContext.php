<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Behat;

use Art4\Requests\Psr\HttpClient;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Hook\AfterSuite;
use Behat\Hook\BeforeSuite;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;
use WpOrg\Requests\Transport\Curl;
use WpOrg\Requests\Transport\Fsockopen;

/**
 * Sends real HTTP requests with the HttpClient to the test server.
 */
final class HttpClientContext implements Context
{
    private const TRANSPORTS = [
        'curl' => Curl::class,
        'fsockopen' => Fsockopen::class,
    ];

    /**
     * @var TestServer|null
     */
    private static $server;

    /**
     * @var array<string,mixed>
     */
    private $options;

    /**
     * @var array<string,string>
     */
    private $requestHeaders = [];

    /**
     * @var string|null
     */
    private $requestBody;

    /**
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * @var Throwable|null
     */
    private $exception;

    /**
     * @var float
     */
    private $durationInSeconds = 0.0;

    public function __construct(string $transport)
    {
        if (!isset(self::TRANSPORTS[$transport])) {
            throw new InvalidArgumentException('Unknown transport "' . $transport . '".');
        }

        $this->options = [
            'transport' => self::TRANSPORTS[$transport],
            'timeout' => 3,
            'connect_timeout' => 3,
        ];
    }

    #[BeforeSuite]
    public static function startServer(): void
    {
        self::$server = new TestServer();
        self::$server->start();
    }

    #[AfterSuite]
    public static function stopServer(): void
    {
        if (self::$server !== null) {
            self::$server->stop();
        }

        self::$server = null;
    }

    #[Given('the client has a timeout of :seconds seconds')]
    public function theClientHasATimeoutOfSeconds(float $seconds): void
    {
        $this->options['timeout'] = $seconds;
        $this->options['connect_timeout'] = $seconds;
    }

    #[Given('the client does not follow redirects')]
    public function theClientDoesNotFollowRedirects(): void
    {
        $this->options['follow_redirects'] = false;
    }

    #[Given('the client follows at most :count redirects')]
    public function theClientFollowsAtMostRedirects(int $count): void
    {
        $this->options['follow_redirects'] = true;
        $this->options['redirects'] = $count;
    }

    #[Given('the request has the header :name with the value :value')]
    public function theRequestHasTheHeader(string $name, string $value): void
    {
        $this->requestHeaders[$name] = $value;
    }

    #[Given('the request has the body :body')]
    public function theRequestHasTheBody(string $body): void
    {
        $this->requestBody = $body;
    }

    #[Given('the request has the body:')]
    public function theRequestHasTheMultilineBody(PyStringNode $body): void
    {
        $this->requestBody = $body->getRaw();
    }

    #[When('I send a :method request to :target')]
    public function iSendARequestTo(string $method, string $target): void
    {
        $client = new HttpClient($this->options);
        $url = strpos($target, '/') === 0 ? TestServer::BASE_URL . $target : $target;

        $this->send($client, $client->createRequest($method, $url));
    }

    #[Then('the response status is :status')]
    public function theResponseStatusIs(int $status): void
    {
        self::expectSame($status, $this->response()->getStatusCode());
    }

    #[Then('the response header :name is :value')]
    public function theResponseHeaderIs(string $name, string $value): void
    {
        self::expectSame($value, $this->response()->getHeaderLine($name));
    }

    #[Then('the response body is :body')]
    public function theResponseBodyIs(string $body): void
    {
        self::expectSame($body, $this->response()->getBody()->__toString());
    }

    #[Then('the response has no body')]
    public function theResponseHasNoBody(): void
    {
        self::expectSame('', $this->response()->getBody()->__toString());
    }

    #[Then('the server received the method :method')]
    public function theServerReceivedTheMethod(string $method): void
    {
        self::expectSame($method, $this->responseJson()['REQUEST_METHOD'] ?? null);
    }

    #[Then('the server received the path :path')]
    public function theServerReceivedThePath(string $path): void
    {
        self::expectSame($path, $this->responseJson()['REQUEST_URI'] ?? null);
    }

    #[Then('the server received the body :body')]
    public function theServerReceivedTheBody(string $body): void
    {
        self::expectSame($body, $this->responseJson()['BODY'] ?? null);
    }

    #[Then('the server received the body:')]
    public function theServerReceivedTheMultilineBody(PyStringNode $body): void
    {
        self::expectSame($body->getRaw(), $this->responseJson()['BODY'] ?? null);
    }

    #[Then('the server received no body')]
    public function theServerReceivedNoBody(): void
    {
        self::expectSame('', $this->responseJson()['BODY'] ?? null);
    }

    #[Then('the server received the header :name with the value :value')]
    public function theServerReceivedTheHeader(string $name, string $value): void
    {
        self::expectSame($value, $this->responseJson()[$this->serverVariable($name)] ?? null);
    }

    #[Then('the server did not receive the header :name')]
    public function theServerDidNotReceiveTheHeader(string $name): void
    {
        self::expectSame(false, array_key_exists($this->serverVariable($name), $this->responseJson()));
    }

    #[Then('the response header :name reports the method :method')]
    public function theResponseHeaderReportsTheMethod(string $name, string $method): void
    {
        self::expectSame($method, self::decodeJson($this->response()->getHeaderLine($name))['REQUEST_METHOD'] ?? null);
    }

    #[Then('a network exception is thrown')]
    public function aNetworkExceptionIsThrown(): void
    {
        $this->expectException(NetworkExceptionInterface::class);
    }

    #[Then('a request exception is thrown')]
    public function aRequestExceptionIsThrown(): void
    {
        $this->expectException(RequestExceptionInterface::class);
    }

    #[Then('the client either returns a response or throws a client exception')]
    public function theClientEitherReturnsAResponseOrThrowsAClientException(): void
    {
        if ($this->exception !== null) {
            $this->expectException(ClientExceptionInterface::class);
        }
    }

    #[Then('the client answers within :seconds seconds')]
    public function theClientAnswersWithinSeconds(float $seconds): void
    {
        if ($this->durationInSeconds >= $seconds) {
            throw new RuntimeException(sprintf('The client took %.2f seconds.', $this->durationInSeconds));
        }
    }

    private function send(HttpClient $client, RequestInterface $request): void
    {
        foreach ($this->requestHeaders as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        if ($this->requestBody !== null) {
            $request = $request->withBody($client->createStream($this->requestBody));
        }

        $start = microtime(true);

        try {
            $this->response = $client->sendRequest($request);
        } catch (Throwable $exception) {
            $this->exception = $exception;
        }

        $this->durationInSeconds = microtime(true) - $start;
    }

    private function response(): ResponseInterface
    {
        if ($this->response === null) {
            throw new RuntimeException('Expected a response. ' . $this->outcome());
        }

        return $this->response;
    }

    /**
     * @return array<mixed>
     */
    private function responseJson(): array
    {
        return self::decodeJson($this->response()->getBody()->__toString());
    }

    /**
     * @return array<mixed>
     */
    private static function decodeJson(string $json): array
    {
        $data = json_decode($json, true);

        if (!is_array($data)) {
            throw new RuntimeException('Expected JSON, got: ' . $json);
        }

        return $data;
    }

    /**
     * PHPUnit assertions need a running PHPUnit, so the steps use this check.
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    private static function expectSame($expected, $actual): void
    {
        if ($expected !== $actual) {
            throw new RuntimeException(sprintf('Expected %s, got %s.', var_export($expected, true), var_export($actual, true)));
        }
    }

    /**
     * @param class-string<Throwable> $class
     */
    private function expectException(string $class): void
    {
        if (!$this->exception instanceof $class) {
            throw new RuntimeException('Expected ' . $class . '. ' . $this->outcome());
        }
    }

    private function serverVariable(string $headerName): string
    {
        return 'HTTP_' . strtoupper(str_replace('-', '_', $headerName));
    }

    private function outcome(): string
    {
        if ($this->exception !== null) {
            return 'Got ' . get_class($this->exception) . ': ' . $this->exception->getMessage();
        }

        return $this->response === null ? 'No request was sent.' : 'Got a response with status ' . $this->response->getStatusCode() . '.';
    }
}
