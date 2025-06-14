<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use Art4\Requests\Psr\Uri;
use Art4\Requests\Tests\TypeProviderHelper;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Http\Message\UriInterface;
use WpOrg\Requests\Iri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class WithUserInfoTest extends TestCase
{
    /**
     * Tests changing the user-info when using withUserInfo().
     *
     * @covers \Art4\Requests\Psr\Uri::withUserInfo
     */
    public function testWithUserInfoReturnsUriInstance(): void
    {
        $uri = Uri::fromIri(new Iri(''));

        TestCase::assertInstanceOf(UriInterface::class, $uri->withUserInfo('user'));
    }

    /**
     * Tests changing the user-info when using withUserInfo().
     *
     * @covers \Art4\Requests\Psr\Uri::withUserInfo
     */
    public function testWithUserInfoReturnsNewInstance(): void
    {
        $uri = Uri::fromIri(new Iri(''));

        TestCase::assertNotSame($uri, $uri->withUserInfo('http'));
    }

    /**
     * Tests receiving an exception when the withUserInfo() method received an invalid input type as `$user`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Uri::withUserInfo
     *
     * @param mixed $input Invalid parameter input.
     */
    #[DataProvider('dataInvalidTypeNotString')]
    public function testWithUserInfoWithoutStringInUserThrowsInvalidArgumentException($input): void
    {
        $uri = Uri::fromIri(new Iri(''));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withUserInfo(): Argument #1 ($user) must be of type string', Uri::class));

        $uri->withUserInfo($input);
    }

    /**
     * Data Provider.
     *
     * @return array<string, mixed>
     */
    public static function dataInvalidTypeNotString(): array
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_STRING);
    }

    /**
     * Tests receiving an exception when the withUserInfo() method received an invalid input type as `$password`.
     *
     * @dataProvider dataInvalidTypeNotStringOrNull
     *
     * @covers \Art4\Requests\Psr\Uri::withUserInfo
     *
     * @param mixed $input Invalid parameter input.
     */
    #[DataProvider('dataInvalidTypeNotStringOrNull')]
    public function testWithUserInfoWithoutStringInPasswordThrowsInvalidArgumentException($input): void
    {
        $uri = Uri::fromIri(new Iri(''));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withUserInfo(): Argument #2 ($password) must be of type null|string', Uri::class));

        $uri->withUserInfo('user', $input);
    }

    /**
     * Data Provider.
     *
     * @return array<string, mixed>
     */
    public static function dataInvalidTypeNotStringOrNull(): array
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_STRING, TypeProviderHelper::GROUP_NULL);
    }

    /**
     * Tests receiving an exception when the withUserInfo() method received an invalid input type as `$password`.
     *
     * @dataProvider dataWithUserInfo
     *
     * @covers \Art4\Requests\Psr\Uri::withUserInfo
     *
     * @param string $user
     * @param null|string $password
     * @param string $expected
     */
    #[DataProvider('dataWithUserInfo')]
    public function testWithUserInfoChangesUserInfo($user, $password, $expected): void
    {
        $uri = Uri::fromIri(new Iri(''));

        $uri = $uri->withUserInfo($user, $password);

        TestCase::assertSame($expected, $uri->getUserInfo());
    }

    /**
     * Data Provider.
     *
     * @return array<string,array<int,null|string>>
     */
    public static function dataWithUserInfo(): array
    {
        return [
            'empty' => ['', null, ''],
            'with the specified user information' => ['mail@example.org', 'password?[]', 'mail%40example.org:password%3F%5B%5D'],
            'Password is optional' => ['user', '', 'user'],
            'Password can bi null' => ['user', null, 'user'],
            'an empty string for the user is equivalent to removing user information' => ['', 'password', ''],
        ];
    }
}
