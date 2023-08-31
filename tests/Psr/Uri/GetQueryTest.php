<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use WpOrg\Requests\Iri;
use Art4\Requests\Psr\Uri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetQueryTest extends TestCase
{
    /**
     * Tests receiving the query when using getQuery().
     *
     * @dataProvider dataGetQuery
     *
     * @covers \Art4\Requests\Psr\Uri::getQuery
     *
     * @return void
     */
    public function testGetQuery(string $input, string $expected)
    {
        $uri = Uri::fromIri(new Iri($input));

        $this->assertSame($expected, $uri->getQuery());
    }

    /**
     * Data Provider.
     *
     * @return array<string,string[]>
     */
    public static function dataGetQuery(): array
    {
        return [
            'empty' => ['', ''],
            'Retrieve the query string of the URI' => ['https://example.com?foo=bar', 'foo=bar'],
            'If no query string is present, return empty string' => ['https://example.com', ''],
            'The leading "?" character is not part of the query' => ['https://example.com?', ''],
            'The value returned MUST be percent-encoded' => ['https://example.com?foo=%26', 'foo=%26'],
            'ampersand ("&") must be passed in encoded form' => ['https://example.com?foo=%26&fo=ba', 'foo=%26&fo=ba'],
        ];
    }
}
