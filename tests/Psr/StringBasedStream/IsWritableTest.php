<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class IsWritableTest extends TestCase
{
    /**
     * Tests receiving false when using isWritable() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::isWritable
     *
     * @return void
     */
    public function testIsWritableReturnsFalse()
    {
        $stream = StringBasedStream::createFromString('');

        $this->assertFalse($stream->isWritable());
    }
}
