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

final class WithSchemeTest extends TestCase
{
    /**
     * Tests changing the scheme when using withScheme().
     *
     * @covers \Art4\Requests\Psr\Uri::withScheme
     */
    public function testWithSchemeReturnsUri(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        TestCase::assertInstanceOf(UriInterface::class, $uri->withScheme('http'));
    }

    /**
     * Tests changing the scheme when using withScheme().
     *
     * @covers \Art4\Requests\Psr\Uri::withScheme
     */
    public function testWithSchemeWithEmptyStringRemovesTheScheme(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $uri = $uri->withScheme('');

        TestCase::assertSame('', $uri->getScheme());
    }

    /**
     * Tests changing the scheme when using withScheme().
     *
     * @covers \Art4\Requests\Psr\Uri::withScheme
     */
    public function testWithSchemeReturnsNewInstance(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        TestCase::assertNotSame($uri, $uri->withScheme('http'));
    }

    /**
     * Tests receiving an exception when the withScheme() method received an invalid input type as `$scheme`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Uri::withScheme
     *
     * @param mixed $input Invalid parameter input.
     */
    #[DataProvider('dataInvalidTypeNotString')]
    public function testWithSchemeWithoutStringThrowsInvalidArgumentException($input): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withScheme(): Argument #1 ($scheme) must be of type string', Uri::class));

        $uri->withScheme($input);
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
     * Tests changing the scheme when using withScheme().
     *
     * @covers \Art4\Requests\Psr\Uri::withScheme
     */
    public function testWithSchemeChangesTheScheme(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $uri = $uri->withScheme('http');

        TestCase::assertSame('http', $uri->getScheme());
    }
}
