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

        $body = $request->getBody()->__toString();

        /*
         * Prepare request data for compatibility with WpOrg\Requests library
         *
         * Handles differences between Guzzle/PSR-7 and WpOrg\Requests:
         * 1. GET/HEAD/DELETE: Always use empty array (no body)
         * 2. All other methods: Keep body as string
         *
         * For POST/PUT/PATCH, the body is passed as a raw string regardless of
         * Content-Type. This ensures URL-encoded bodies with repeated parameter
         * names (e.g. "text=foo&text=bar") are preserved correctly, since
         * parse_str() would collapse them into a single value.
         */
        $data = ($body === '' || $body === '[]') ? [] : $body;

        // If $data contains a non-empty body, strip Content-Length
        // and Transfer-Encoding so the server doesn't hang waiting for bytes
        // that will never arrive.
        if ($data === []) {
            foreach (array_keys($headers) as $name) {
                if (strcasecmp($name, 'Content-Length') === 0 || strcasecmp($name, 'Transfer-Encoding') === 0) {
                    unset($headers[$name]);
                }
            }
        }

        $options = $this->options;
        // Force data_format='body' when passing a raw string.
        // WpOrg\Requests defaults to 'query' for GET/HEAD/DELETE, which calls
        // http_build_query() on $data and fatals with TypeError if it's a string.
        if (is_string($data)) {
            $options['data_format'] = 'body';
        }

        try {
            $response = Requests::request(
                $request->getUri()->__toString(),
                $headers,
                $data, /** @phpstan-ignore argument.type(# $data must be array|null, but underlying transport classes accept array|string; prepareRequestData() returns array<string,string>|string for proper handling) */
                $request->getMethod(),
                $options
            );
        } catch (Transport $th) {
            throw new NetworkException($request, $th);
        } catch (Exception $th) {
            throw new RequestException($request, $th);
        }

        return Response::fromResponse($response);
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
     * @_throws \RuntimeException If the file cannot be opened.
     * @_throws \InvalidArgumentException If the mode is invalid.
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
