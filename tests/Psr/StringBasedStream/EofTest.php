<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\StringBasedStream;

use Art4\Requests\Psr\StringBasedStream;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class EofTest extends TestCase
{
    /**
     * Tests receiving true when using eof() method.
     *
     * @covers \Art4\Requests\Psr\StringBasedStream::eof
     *
     * @return void
     */
    public function testEofReturnsTrue()
    {
        $stream = StringBasedStream::createFromString('');

        $this->assertTrue($stream->eof());
    }
}
