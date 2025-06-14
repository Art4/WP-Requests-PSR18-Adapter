<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use Art4\Requests\Psr\Uri;
use PHPUnit\Framework\Attributes\DataProvider;
use WpOrg\Requests\Iri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetAuthorityTest extends TestCase
{
    /**
     * Tests receiving the authority when using getAuthority().
     *
     * @dataProvider dataGetAuthority
     *
     * @covers \Art4\Requests\Psr\Uri::getAuthority
     */
    #[DataProvider('dataGetAuthority')]
    public function testGetAuthority(string $input, string $expected): void
    {
        $uri = Uri::fromIri(new Iri($input));

        TestCase::assertSame($expected, $uri->getAuthority());
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
