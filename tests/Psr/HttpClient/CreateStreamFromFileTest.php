<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\HttpClient;

use Art4\Requests\Psr\HttpClient;
use Exception;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class CreateStreamFromFileTest extends TestCase
{
    /**
     * Tests receiving an exception when using createStreamFromFile().
     *
     * @covers \Art4\Requests\Psr\HttpClient::createStreamFromFile
     *
     * @return void
     */
    public function testCreateStreamFromFileThrowsException()
    {
        $httpClient = new HttpClient();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Art4\Requests\Psr\HttpClient::createStreamFromFile() is not yet implemented.');

        $httpClient->createStreamFromFile('path/to/filename.txt');
    }
}
