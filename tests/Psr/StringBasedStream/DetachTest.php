<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class DetachTest extends TestCase
{
    /**
     * Tests receiving null when using detach() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::detach
     *
     * @return void
     */
    public function testDetachReturnsNull()
    {
        $stream = StringBasedStream::createFromString('');

        $this->assertNull($stream->detach());
    }
}
