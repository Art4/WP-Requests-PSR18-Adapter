<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use Art4\Requests\Psr\Uri;
use Psr\Http\Message\UriInterface;
use WpOrg\Requests\Iri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class FromIriTest extends TestCase
{
    /**
     * Tests receiving an Uri instance when using fromIri().
     *
     * @covers \Art4\Requests\Psr\Uri::fromIri
     * @covers \Art4\Requests\Psr\Uri::__construct
     */
    public function testFromIriReturnsUri(): void
    {
        TestCase::assertInstanceOf(
            UriInterface::class,
            Uri::fromIri(new Iri('https://example.org'))
        );
    }

    /**
     * Tests Iri instance is immutable when using fromIri().
     *
     * @covers \Art4\Requests\Psr\Uri::withScheme
     */
    public function testFromIriHasImmutableIriInstance(): void
    {
        $iri = new Iri('https://example.org');
        $uri = Uri::fromIri($iri);

        $iri->scheme = 'http';

        TestCase::assertSame('https', $uri->getScheme());
    }
}
