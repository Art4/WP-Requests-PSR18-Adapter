<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use Art4\Requests\Psr\Uri;
use PHPUnit\Framework\Attributes\DataProvider;
use WpOrg\Requests\Iri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetUserInfoTest extends TestCase
{
    /**
     * Tests receiving the user-info when using getUserInfo().
     *
     * @dataProvider dataGetUserInfo
     *
     * @covers \Art4\Requests\Psr\Uri::getUserInfo
     */
    #[DataProvider('dataGetUserInfo')]
    public function testGetUserInfo(string $input, string $expected): void
    {
        $uri = Uri::fromIri(new Iri($input));

        TestCase::assertSame($expected, $uri->getUserInfo());
    }

    /**
     * Data Provider.
     *
     * @return array<string,string[]>
     */
    public static function dataGetUserInfo(): array
    {
        return [
            'empty' => ['', ''],
            'without user-info' => ['https://@example.org', ''],
            'with user-info and password' => ['https://user:pass@example.org', 'user:pass'],
            'with user-info' => ['https://user@example.org', 'user'],
            'with password' => ['https://:pass@example.org', ':pass'],
        ];
    }
}
