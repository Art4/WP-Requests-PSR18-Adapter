<?php

declare(strict_types=1);
/**
 * Transport Exception
 *
 * @package Requests\Exceptions
 */

namespace Art4\Requests\Exception\Psr;

use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestInterface;
use WpOrg\Requests\Exception;
use WpOrg\Requests\Exception\Transport;

/**
 * Network Exception
 *
 * @package Requests\Exceptions
 *
 * Thrown when the request cannot be completed because of network issues.
 *
 * There is no response object as this exception is thrown when no response has been received.
 *
 * Example: the target host name can not be resolved or the connection failed.
 */
class NetworkException extends Exception implements NetworkExceptionInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Create a new exception
     *
     * @param RequestInterface $request
     * @param Transport        $previous
     */
    public function __construct(RequestInterface $request, Transport $previous)
    {
        parent::__construct(
            $previous->getMessage(),
            $previous->getType(),
            $previous->getData(),
            $previous->getCode()
        );

        $this->request = $request;
    }

    /**
     * Returns the request.
     *
     * The request object MAY be a different object from the one passed to ClientInterface::sendRequest()
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
