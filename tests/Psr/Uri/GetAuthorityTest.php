<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use WpOrg\Requests\Iri;
use Art4\Requests\Psr\Uri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetAuthorityTest extends TestCase
{
    /**
     * Tests receiving the authority when using getAuthority().
     *
     * @dataProvider dataGetAuthority
     *
     * @covers \Art4\Requests\Psr\Uri::getAuthority
     *
     * @return void
     */
    public function testGetAuthority(string $input, string $expected)
    {
        $uri = Uri::fromIri(new Iri($input));

        $this->assertSame($expected, $uri->getAuthority());
    }

    /**
     * Data Provider.
     *
     * @return array<string,string[]>
     */
    public static function dataGetAuthority(): array
    {
        return [
            'empty' => ['', ''],
            'basic' => ['https://example.org', 'example.org'],
            'without host' => ['https://', ''],
            'with port' => ['https://example.org:12345', 'example.org:12345'],
            'with user-info and password' => ['https://user:pass@example.org', 'user:pass@example.org'],
            'with user-info' => ['https://user@example.org', 'user@example.org'],
            'with password' => ['https://:pass@example.org', ':pass@example.org'],
            'with user-info and port' => ['https://user@example.org:12345', 'user@example.org:12345'],
        ];
    }
}
