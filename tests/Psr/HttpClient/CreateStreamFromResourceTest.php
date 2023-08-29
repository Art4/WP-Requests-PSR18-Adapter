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
     *
     * @return void
     */
    public function testCreateStreamFromResourceThrowsException()
    {
        $httpClient = new HttpClient();

        $resource = fopen('php://temp', 'r+');

        if ($resource === false) {
            throw new Exception('could not create resource');
        }

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Art4\Requests\Psr\HttpClient::createStreamFromResource() is not yet implemented.');

        $httpClient->createStreamFromResource($resource);
    }
}
