<?php
/**
 * Transport Exception
 *
 * @package Requests\Exceptions
 */

namespace Art4\Requests\Exception\Psr;

use Psr\Http\Client\ClientExceptionInterface;
use WpOrg\Requests\Exception;

/**
 * Client Exception
 *
 * @package Requests\Exceptions
 */
class ClientException extends Exception implements ClientExceptionInterface {}
