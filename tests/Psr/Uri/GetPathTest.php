<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use Art4\Requests\Psr\Uri;
use PHPUnit\Framework\Attributes\DataProvider;
use WpOrg\Requests\Iri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetPathTest extends TestCase
{
    /**
     * Tests receiving the path when using getPath().
     *
     * @dataProvider dataGetPath
     *
     * @covers \Art4\Requests\Psr\Uri::getPath
     *
     * @param string $input
     * @param string $expected
     */
    #[DataProvider('dataGetPath')]
    public function testGetPath($input, $expected): void
    {
        $uri = Uri::fromIri(new Iri($input));

        TestCase::assertSame($expected, $uri->getPath());
    }

    /**
     * Data Provider.
     *
     * @return array<string,string[]>
     */
    public static function dataGetPath(): array
    {
        return [
            'Retrieve the path component of the URI' => ['https://example.org/path', '/path'],
            'The path can be empty' => ['', ''],
            'The path can be slash' => ['/', '/'],
            'The path can be two slashes' => ['//', '/'],
            'The path can be absolute (starting with a slash)' => ['/path', '/path'],
            'The path can be rootless (not starting with a slash)' => ['path', 'path'],
            'The value returned MUST be percent-encoded' => ['%2F', '%2F'],
        ];
    }
}
