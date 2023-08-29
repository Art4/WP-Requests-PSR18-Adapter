<?php

declare(strict_types=1);
/**
 * PSR-7 RequestInterface implementation
 */

namespace Art4\Requests\Psr;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use WpOrg\Requests\Exception\InvalidArgument;

/**
 * PSR-7 RequestInterface implementation
 *
 * Representation of an outgoing, client-side request.
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - Protocol version
 * - HTTP method
 * - URI
 * - Headers
 * - Message body
 *
 * During construction, implementations MUST attempt to set the Host header from
 * a provided URI if no Host header is provided.
 *
 * Requests are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 */
final class Request implements RequestInterface
{
    use MessageHeaderTrait;

    /**
     * create Request with method and uri
     *
     * @param string $method
     * @param UriInterface $uri
     *
     * @return Request
     */
    public static function withMethodAndUri(string $method, UriInterface $uri)
    {
        $request = new self($method, $uri);
        $request->body = StringBasedStream::createFromString('');

        return $request;
    }

    /**
     * @var string
     */
    private $method;

    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * @var string
     */
    private $requestTarget = '';

    /**
     * @var string
     */
    private $protocolVersion = '1.1';

    /**
     * @var StreamInterface
     */
    private $body;

    /**
     * Constructor
     *
     * @param string $method
     * @param UriInterface $uri
     *
     * @return Request
     */
    private function __construct($method, UriInterface $uri)
    {
        $this->method = $method;
        $this->setUri($uri, false);
    }

    /**
     * Retrieves the message's request target.
     *
     * Retrieves the message's request-target either as it will appear (for
     * clients), as it appeared at request (for servers), or as it was
     * specified for the instance (see withRequestTarget()).
     *
     * In most cases, this will be the origin-form of the composed URI,
     * unless a value was provided to the concrete implementation (see
     * withRequestTarget() below).
     *
     * If no URI is available, and no request-target has been specifically
     * provided, this method MUST return the string "/".
     *
     * @return string
     */
    public function getRequestTarget(): string
    {
        if ($this->requestTarget !== '') {
            return $this->requestTarget;
        }

        $target = $this->uri->getPath();

        if ($target === '') {
            $target = '/';
        }

        $query = $this->uri->getQuery();

        if ($query !== '') {
            $target .= '?' . $query;
        }

        return $target;
    }

    /**
     * Return an instance with the specific request-target.
     *
     * If the request needs a non-origin-form request-target — e.g., for
     * specifying an absolute-form, authority-form, or asterisk-form —
     * this method may be used to create an instance with the specified
     * request-target, verbatim.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     *
     * @see http://tools.ietf.org/html/rfc7230#section-5.3 (for the various
     *     request-target forms allowed in request messages)
     * @param string $requestTarget
     * @return static
     */
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        if ($requestTarget === '') {
            $requestTarget = '/';
        }

        $request = clone($this);
        $request->requestTarget = $requestTarget;

        return $request;
    }

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Return an instance with the provided HTTP method.
     *
     * While HTTP method names are typically all uppercase characters, HTTP
     * method names are case-sensitive and thus implementations SHOULD NOT
     * modify the given string.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request method.
     *
     * @param string $method Case-sensitive method.
     * @return static
     * @throws \InvalidArgumentException for invalid HTTP methods.
     */
    public function withMethod(string $method): RequestInterface
    {
        $request = clone($this);
        $request->method = $method;

        return $request;
    }

    /**
     * Retrieves the URI instance.
     *
     * This method MUST return a UriInterface instance.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.3
     * @return UriInterface Returns a UriInterface instance
     *     representing the URI of the request.
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * Returns an instance with the provided URI.
     *
     * This method MUST update the Host header of the returned request by
     * default if the URI contains a host component. If the URI does not
     * contain a host component, any pre-existing Host header MUST be carried
     * over to the returned request.
     *
     * You can opt-in to preserving the original state of the Host header by
     * setting `$preserveHost` to `true`. When `$preserveHost` is set to
     * `true`, this method interacts with the Host header in the following ways:
     *
     * - If the Host header is missing or empty, and the new URI contains
     *   a host component, this method MUST update the Host header in the returned
     *   request.
     * - If the Host header is missing or empty, and the new URI does not contain a
     *   host component, this method MUST NOT update the Host header in the returned
     *   request.
     * - If a Host header is present and non-empty, this method MUST NOT update
     *   the Host header in the returned request.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new UriInterface instance.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.3
     * @param UriInterface $uri New request URI to use.
     * @param bool $preserveHost Preserve the original state of the Host header.
     * @return static
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $request = clone($this);
        $request->setUri($uri, $preserveHost);

        return $request;
    }

    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * The string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * Return an instance with the specified HTTP protocol version.
     *
     * The version string MUST contain only the HTTP version number (e.g.,
     * "1.1", "1.0").
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new protocol version.
     *
     * @param string $version HTTP protocol version
     * @return static
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        $request = clone($this);
        $request->protocolVersion = $version;

        return $request;
    }

    /**
     * Gets the body of the message.
     *
     * @return StreamInterface Returns the body as a stream.
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * Return an instance with the specified message body.
     *
     * The body MUST be a StreamInterface object.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return a new instance that has the
     * new body stream.
     *
     * @param StreamInterface $body Body.
     * @return static
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        $request = clone($this);
        $request->body = $body;

        return $request;
    }

    /**
     * Set the URI and update the Host header.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.3
     * @param UriInterface $uri New request URI to use.
     * @param bool $preserveHost Preserve the original state of the Host header.
     * @return void
     */
    private function setUri(UriInterface $uri, $preserveHost)
    {
        $this->uri = $uri;

        $host = $uri->getHost();

        if ($host !== '' && $this->getHeaderLine('Host') === '') {
            $this->updateHeader('Host', [$host]);
        } elseif ($host !== '' && $preserveHost === false) {
            $this->updateHeader('Host', [$host]);
        }
    }
}
