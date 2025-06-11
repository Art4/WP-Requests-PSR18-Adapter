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

final class WithQueryTest extends TestCase
{
    /**
     * Tests changing the query when using withQuery().
     *
     * @covers \Art4\Requests\Psr\Uri::withQuery
     */
    public function testWithQueryReturnsUri(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        TestCase::assertInstanceOf(UriInterface::class, $uri->withQuery('foo=bar'));
    }

    /**
     * Tests changing the query when using withQuery().
     *
     * @covers \Art4\Requests\Psr\Uri::withQuery
     */
    public function testWithQueryReturnsNewInstance(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        TestCase::assertNotSame($uri, $uri->withQuery('foo=bar'));
    }

    /**
     * Tests receiving an exception when the withQuery() method received an invalid input type as `$query`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Uri::withQuery
     *
     * @param mixed $input Invalid parameter input.
     */
    #[DataProvider('dataInvalidTypeNotString')]
    public function testWithQueryWithoutStringThrowsInvalidArgumentException($input): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withQuery(): Argument #1 ($query) must be of type string', Uri::class));

        $uri->withQuery($input);
    }

    /**
     * Data Provider.
     *
     * @return array<string, mixed>
     */
    public static function dataInvalidTypeNotString()
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_STRING);
    }

    /**
     * Tests changing the query when using withQuery().
     *
     * @dataProvider dataWithQuery
     *
     * @covers \Art4\Requests\Psr\Uri::withQuery
     *
     * @param string $input
     * @param string $expected
     */
    #[DataProvider('dataWithQuery')]
    public function testWithQueryChangesTheQuery($input, $expected): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $uri = $uri->withQuery($input);

        TestCase::assertSame($expected, $uri->getQuery());
    }

    /**
     * Data Provider.
     *
     * @return array<string,string[]>
     */
    public static function dataWithQuery(): array
    {
        return [
            'Return an instance with the specified query string' => ['query', 'query'],
            'Users can provide encoded query characters' => ['filter%5Bstatus%5D=open', 'filter%5Bstatus%5D=open'],
            'Users can provide decoded query characters' => ['filter[status]=open', 'filter%5Bstatus%5D=open'],
            'An empty query string value is equivalent to removing the query string' => ['', ''],
        ];
    }
}
