<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use WpOrg\Requests\Iri;
use Art4\Requests\Psr\Uri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class WithQueryTest extends TestCase
{
    /**
     * Tests changing the query when using withQuery().
     *
     * @covers \Art4\Requests\Psr\Uri::withQuery
     *
     * @return void
     */
    public function testWithQueryReturnsUri()
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->assertInstanceOf(UriInterface::class, $uri->withQuery('foo=bar'));
    }

    /**
     * Tests changing the query when using withQuery().
     *
     * @covers \Art4\Requests\Psr\Uri::withQuery
     *
     * @return void
     */
    public function testWithQueryReturnsNewInstance()
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->assertNotSame($uri, $uri->withQuery('foo=bar'));
    }

    /**
     * Tests receiving an exception when the withQuery() method received an invalid input type as `$query`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Uri::withQuery
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    public function testWithQueryWithoutStringThrowsInvalidArgumentException($input)
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withQuery(): Argument #1 ($query) must be of type string', Uri::class));

        $uri = $uri->withQuery($input);
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
     *
     * @return void
     */
    public function testWithQueryChangesTheQuery($input, $expected)
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $uri = $uri->withQuery($input);

        $this->assertSame($expected, $uri->getQuery());
    }

    /**
     * Data Provider.
     *
     * @return array<string,string[]>
     */
    public static function dataWithQuery()
    {
        return [
            'Return an instance with the specified query string' => ['query', 'query'],
            'Users can provide encoded query characters' => ['filter%5Bstatus%5D=open', 'filter%5Bstatus%5D=open'],
            'Users can provide decoded query characters' => ['filter[status]=open', 'filter%5Bstatus%5D=open'],
            'An empty query string value is equivalent to removing the query string' => ['', ''],
        ];
    }
}
