<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\HttpClient;

use Art4\Requests\Psr\HttpClient;
use Psr\Http\Message\StreamInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class CreateStreamTest extends TestCase
{
    /**
     * Tests receiving a Stream when using createStream().
     *
     * @covers \Art4\Requests\Psr\HttpClient::createStream
     */
    public function testCreateStreamReturnsStream(): void
    {
        $httpClient = new HttpClient();

        TestCase::assertInstanceOf(
            StreamInterface::class,
            $httpClient->createStream('')
        );
    }
}
