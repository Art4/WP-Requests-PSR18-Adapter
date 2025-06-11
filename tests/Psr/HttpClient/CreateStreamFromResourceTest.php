<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\HttpClient;

use Art4\Requests\Psr\HttpClient;
use Exception;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class CreateStreamFromResourceTest extends TestCase
{
    /**
     * Tests receiving an exception when using createStreamFromResource().
     *
     * @covers \Art4\Requests\Psr\HttpClient::createStreamFromResource
     */
    public function testCreateStreamFromResourceThrowsException(): void
    {
        $httpClient = new HttpClient();

        $resource = fopen('php://temp', 'r+');

        if ($resource === false) {
            throw new Exception('could not create resource');
        }

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(HttpClient::class . '::createStreamFromResource() is not yet implemented.');

        $httpClient->createStreamFromResource($resource);
    }
}
