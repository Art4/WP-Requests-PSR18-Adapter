<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use WpOrg\Requests\Iri;
use Art4\Requests\Psr\Uri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetUserInfoTest extends TestCase
{
    /**
     * Tests receiving the user-info when using getUserInfo().
     *
     * @dataProvider dataGetUserInfo
     *
     * @covers \Art4\Requests\Psr\Uri::getUserInfo
     *
     * @return void
     */
    public function testGetUserInfo(string $input, string $expected)
    {
        $uri = Uri::fromIri(new Iri($input));

        $this->assertSame($expected, $uri->getUserInfo());
    }

    /**
     * Data Provider.
     *
     * @return array<string,string[]>
     */
    public static function dataGetUserInfo()
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
