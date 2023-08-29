<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use Psr\Http\Message\UriInterface;
use WpOrg\Requests\Iri;
use Art4\Requests\Psr\Uri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class FromIriTest extends TestCase
{
    /**
     * Tests receiving an Uri instance when using fromIri().
     *
     * @covers \Art4\Requests\Psr\Uri::fromIri
     * @covers \Art4\Requests\Psr\Uri::__construct
     *
     * @return void
     */
    public function testFromIriReturnsUri()
    {
        $this->assertInstanceOf(
            UriInterface::class,
            Uri::fromIri(new Iri('https://example.org'))
        );
    }

    /**
     * Tests Iri instance is immutable when using fromIri().
     *
     * @covers \Art4\Requests\Psr\Uri::withScheme
     *
     * @return void
     */
    public function testFromIriHasImmutableIriInstance()
    {
        $iri = new Iri('https://example.org');
        $uri = Uri::fromIri($iri);

        $iri->scheme = 'http';

        $this->assertSame('https', $uri->getScheme());
    }
}
