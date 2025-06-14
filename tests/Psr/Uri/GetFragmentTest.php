<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use Art4\Requests\Psr\Uri;
use PHPUnit\Framework\Attributes\DataProvider;
use WpOrg\Requests\Iri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetFragmentTest extends TestCase
{
    /**
     * Tests receiving the fragment when using getFragment().
     *
     * @dataProvider dataGetFragment
     *
     * @covers \Art4\Requests\Psr\Uri::getFragment
     */
    #[DataProvider('dataGetFragment')]
    public function testGetFragment(string $input, string $expected): void
    {
        $uri = Uri::fromIri(new Iri($input));

        TestCase::assertSame($expected, $uri->getFragment());
    }

    /**
     * Data Provider.
     *
     * @return array<string,string[]>
     */
    public static function dataGetFragment(): array
    {
        return [
            'empty' => ['', ''],
            'Retrieve the fragment component of the URI' => ['https://example.org#fragment', 'fragment'],
            'If no fragment is present, return an empty string' => ['https://example.org', ''],
            'The leading "#" character is not part of the fragment' => ['https://example.org#', ''],
            'The value returned MUST be percent-encoded' => ['#fragment[]', 'fragment%5B%5D'],
        ];
    }
}
