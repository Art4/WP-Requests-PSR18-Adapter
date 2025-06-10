<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use Art4\Requests\Psr\Uri;
use WpOrg\Requests\Iri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetSchemeTest extends TestCase
{
    /**
     * Tests receiving the scheme when using getScheme().
     *
     * @covers \Art4\Requests\Psr\Uri::getScheme
     */
    public function testGetScheme(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        TestCase::assertSame('https', $uri->getScheme());
    }

    /**
     * Tests receiving the scheme when using getScheme().
     *
     * @covers \Art4\Requests\Psr\Uri::getScheme
     */
    public function testGetSchemeReturnEmptyString(): void
    {
        $uri = Uri::fromIri(new Iri('example.org'));

        TestCase::assertSame('', $uri->getScheme());
    }
}
