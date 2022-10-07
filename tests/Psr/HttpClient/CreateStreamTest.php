<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\HttpClient;

use Psr\Http\Message\StreamInterface;
use Art4\Requests\Psr\HttpClient;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class CreateStreamTest extends TestCase
{
    /**
     * Tests receiving a Stream when using createStream().
     *
     * @covers \Art4\Requests\Psr\HttpClient::createStream
     *
     * @return void
     */
    public function testCreateStreamReturnsStream()
    {
        $httpClient = new HttpClient();

        $this->assertInstanceOf(
            StreamInterface::class,
            $httpClient->createStream('')
        );
    }
}
