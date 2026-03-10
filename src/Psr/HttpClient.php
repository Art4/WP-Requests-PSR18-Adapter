<?php

declare(strict_types=1);
/**
 * Implementation for PSR-18 HTTP client and some PSR-17 factories
 */

namespace Art4\Requests\Psr;

use Art4\Requests\Exception\Psr\NetworkException;
use Art4\Requests\Exception\Psr\RequestException;
use Exception as GlobalException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use WpOrg\Requests\Exception;
use WpOrg\Requests\Exception\InvalidArgument;
use WpOrg\Requests\Exception\Transport;
use WpOrg\Requests\Iri;
use WpOrg\Requests\Requests;

/**
 * HTTP implementation for PSR-17 and PSR-18
 */
final class HttpClient implements RequestFactoryInterface, StreamFactoryInterface, ClientInterface
{
    /**
     * @var array<string,mixed>
     */
    private $options = [];

    /**
     * Constructor
     *
     * @param array<string,mixed> $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Create a new request.
     *
     * @param string $method The HTTP method associated with the request.
     * @param UriInterface|string $uri The URI associated with the request.
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        if (! $uri instanceof UriInterface) {
            if (!is_string($uri)) {
                throw InvalidArgument::create(2, '$uri', UriInterface::class . '|string', gettype($uri));
            }

            $uri = Uri::fromIri(new Iri($uri));
        }

        if ($method === '') {
            $method = 'GET';
        }

        return Request::withMethodAndUri($method, $uri);
    }

    /**
     * Create a new stream from a string.
     *
     * The stream SHOULD be created with a temporary resource.
     *
     * @param string $content String content with which to populate the stream.
     */
    public function createStream(string $content = ''): StreamInterface
    {
        return StringBasedStream::createFromString($content);
    }

    /**
     * Sends a PSR-7 request and returns a PSR-7 response.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $headers = [];

        foreach (array_keys($request->getHeaders()) as $key) {
            $headers[$key] = $request->getHeaderLine($key);
        }

        // Prepare the request body/data for WpOrg\Requests library
        $data = $this->prepareRequestData($request);

        try {
            $response = Requests::request(
                $request->getUri()->__toString(),
                $headers,
                $data,
                $request->getMethod(),
                $this->options
            );
        } catch (Transport $th) {
            throw new NetworkException($request, $th);
        } catch (Exception $th) {
            throw new RequestException($request, $th);
        }

        return Response::fromResponse($response);
    }

    /**
     * Prepare request data for compatibility with WpOrg\Requests library
     *
     * Handles differences between Guzzle/PSR-7 and WpOrg\Requests:
     * 1. GET/HEAD/DELETE: Always use empty array (no body)
     * 2. POST/PUT/PATCH with JSON: Keep as string
     * 3. Form data: Parse to array if Content-Type is form-urlencoded
     *
     * @param RequestInterface $request
     * @return array|string
     */
    private function prepareRequestData(RequestInterface $request)
    {
        $method = strtoupper($request->getMethod());
        $body = $request->getBody()->__toString();

        // For GET, HEAD, DELETE requests: never send body data
        // WpOrg\Requests uses data_format='query' for these methods,
        // which calls http_build_query() expecting an array
        if (in_array($method, ['GET', 'HEAD', 'DELETE'])) {
            return [];
        }

        // For requests with empty body, return empty array
        if ($body === '' || $body === '[]') {
            return [];
        }

        // Check Content-Type header to determine how to handle the body
        $contentType = $request->getHeaderLine('Content-Type');

        // If Content-Type is application/x-www-form-urlencoded, parse to array
        // This allows WpOrg\Requests to properly handle form data
        if (strpos($contentType, 'application/x-www-form-urlencoded') === 0) {
            parse_str($body, $parsedData);
            return $parsedData;
        }

        // For all other content types (application/json, text/xml, etc.),
        // return body as string - the transport layer will handle it correctly
        return $body;
    }

    /**
     * Create a stream from an existing file.
     *
     * The file MUST be opened using the given mode, which may be any mode
     * supported by the `fopen` function.
     *
     * The `$filename` MAY be any string supported by `fopen()`.
     *
     * @param string $filename Filename or stream URI to use as basis of stream.
     * @param string $mode Mode with which to open the underlying filename/stream.
     *
     * @throws \RuntimeException If the file cannot be opened.
     * @throws \InvalidArgumentException If the mode is invalid.
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        throw new GlobalException(__METHOD__ . '() is not yet implemented.');
    }

    /**
     * Create a new stream from an existing resource.
     *
     * The stream MUST be readable and may be writable.
     *
     * @param resource $resource PHP resource to use as basis of stream.
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        throw new GlobalException(__METHOD__ . '() is not yet implemented.');
    }
}
