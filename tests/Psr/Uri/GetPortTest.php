<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use WpOrg\Requests\Iri;
use Art4\Requests\Psr\Uri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetPortTest extends TestCase
{
    /**
     * Tests receiving the port when using getPort().
     *
     * @dataProvider dataGetPort
     *
     * @covers \Art4\Requests\Psr\Uri::getPort
     *
     * @param null|int $expected
     *
     * @return void
     */
    public function testGetPort(string $input, $expected)
    {
        $uri = Uri::fromIri(new Iri($input));

        $this->assertSame($expected, $uri->getPort());
    }

    /**
     * Data Provider.
     *
     * @return array<string,array<string|int|null>>
     */
    public static function dataGetPort()
    {
        return [
            'retrieve the port component of the URI' => ['https://example.org:12345', 12345],
            'port is present, and it is non-standard for the current scheme, return integer' => ['http://example.org:443', 443],
            'port is the standard port used with the current scheme, return null' => ['https://example.org:443', null],
            'no port is present, and no scheme is present, return null' => ['example.org', null],
            'no port is present, but a scheme is present, SHOULD return null' => ['https://example.org', null],
            'empty' => ['', null],
        ];
    }
}
