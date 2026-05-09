<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Integration;

use Art4\Requests\Psr\HttpClient;
use Http\Client\Tests\HttpClientTest;
use Psr\Http\Client\ClientInterface;

final class HttpClientIntegrationTest extends HttpClientTest
{
    protected function createHttpAdapter(): ClientInterface
    {
        return new HttpClient([
            'follow_redirects' => false,
            'timeout' => 5,
            'connect_timeout' => 5,
        ]);
    }
}
