<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use WpOrg\Requests\Iri;
use Art4\Requests\Psr\Uri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class WithSchemeTest extends TestCase
{
    /**
     * Tests changing the scheme when using withScheme().
     *
     * @covers \Art4\Requests\Psr\Uri::withScheme
     *
     * @return void
     */
    public function testWithSchemeReturnsUri()
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->assertInstanceOf(UriInterface::class, $uri->withScheme('http'));
    }

    /**
     * Tests changing the scheme when using withScheme().
     *
     * @covers \Art4\Requests\Psr\Uri::withScheme
     *
     * @return void
     */
    public function testWithSchemeWithEmptyStringRemovesTheScheme()
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $uri = $uri->withScheme('');

        $this->assertSame('', $uri->getScheme());
    }

    /**
     * Tests changing the scheme when using withScheme().
     *
     * @covers \Art4\Requests\Psr\Uri::withScheme
     *
     * @return void
     */
    public function testWithSchemeReturnsNewInstance()
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->assertNotSame($uri, $uri->withScheme('http'));
    }

    /**
     * Tests receiving an exception when the withScheme() method received an invalid input type as `$scheme`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Uri::withScheme
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    public function testWithSchemeWithoutStringThrowsInvalidArgumentException($input)
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withScheme(): Argument #1 ($scheme) must be of type string', Uri::class));

        $uri = $uri->withScheme($input);
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
     * Tests changing the scheme when using withScheme().
     *
     * @covers \Art4\Requests\Psr\Uri::withScheme
     *
     * @return void
     */
    public function testWithSchemeChangesTheScheme()
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $uri = $uri->withScheme('http');

        $this->assertSame('http', $uri->getScheme());
    }
}
