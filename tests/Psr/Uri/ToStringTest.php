<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use Art4\Requests\Psr\Uri;
use PHPUnit\Framework\Attributes\DataProvider;
use WpOrg\Requests\Iri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class ToStringTest extends TestCase
{
    /**
     * Tests if a scheme is present, it MUST be suffixed by ":" when using __toString().
     *
     * @covers \Art4\Requests\Psr\Uri::__toString
     */
    public function testToStringIfSchemeIsPresent(): void
    {
        $uri = Uri::fromIri(new Iri(''));
        $uri = $uri->withScheme('http');

        TestCase::assertSame('http:', $uri->__toString());
    }

    /**
     * Tests if an authority is present, it MUST be prefixed by "//" when using __toString().
     *
     * @covers \Art4\Requests\Psr\Uri::__toString
     */
    public function testToStringIfAuthorityIsPresent(): void
    {
        $uri = Uri::fromIri(new Iri(''));
        $uri = $uri->withHost('host');

        TestCase::assertSame('//host/', $uri->__toString());
    }

    /**
     * Tests the path can be concatenated without delimiters when using __toString().
     *
     * @covers \Art4\Requests\Psr\Uri::__toString
     */
    public function testToStringThePathIsConcatenated(): void
    {
        $uri = Uri::fromIri(new Iri(''));
        $uri = $uri->withPath('path');

        TestCase::assertSame('path', $uri->__toString());
    }

    /**
     * Tests if the path is rootless and an authority is present when using __toString().
     *
     * @covers \Art4\Requests\Psr\Uri::__toString
     */
    public function testToStringIfThePathIsRootslessAndAuthorityIsPresent(): void
    {
        $uri = Uri::fromIri(new Iri(''));
        $uri = $uri->withPath('rootlesspath');
        $uri = $uri->withHost('host');

        TestCase::assertSame('//host/rootlesspath', $uri->__toString());
    }

    /**
     * Tests if the path is starting with more than one "/" and no authority is present when using __toString().
     *
     * @covers \Art4\Requests\Psr\Uri::__toString
     */
    public function testToStringIfThePathIsStartingWithMultibleSlashesAndNoAuthorityIsPresent(): void
    {
        $uri = Uri::fromIri(new Iri(''));
        $uri = $uri->withPath('//path');

        TestCase::assertSame('/path', $uri->__toString());
    }

    /**
     * Tests if a query is present, it MUST be prefixed by "?" when using __toString().
     *
     * @covers \Art4\Requests\Psr\Uri::__toString
     */
    public function testToStringIfQueryIsPresent(): void
    {
        $uri = Uri::fromIri(new Iri(''));
        $uri = $uri->withQuery('foo=bar');

        TestCase::assertSame('?foo=bar', $uri->__toString());
    }

    /**
     * Tests if a fragment is present, it MUST be prefixed by "#" when using __toString().
     *
     * @covers \Art4\Requests\Psr\Uri::__toString
     */
    public function testToStringIfFragmentIsPresent(): void
    {
        $uri = Uri::fromIri(new Iri(''));
        $uri = $uri->withFragment('fragment');

        TestCase::assertSame('#fragment', $uri->__toString());
    }

    /**
     * Tests the result when using __toString().
     *
     * @dataProvider dataToString
     *
     * @covers \Art4\Requests\Psr\Uri::__toString
     */
    #[DataProvider('dataToString')]
    public function testToStringReturnsCorrectString(string $input, string $expected): void
    {
        $uri = Uri::fromIri(new Iri($input));

        TestCase::assertSame($expected, $uri->__toString());
    }

    /**
     * Data Provider.
     *
     * @return array<string,string[]>
     */
    public static function dataToString(): array
    {
        return [
            'empty' => ['', ''],
            'only root path' => ['/', '/'],
            'with suffixed "/"' => ['http://host', 'http://host/'],
            'with authority' => ['http://user@host', 'http://user@host/'],
        ];
    }
}
