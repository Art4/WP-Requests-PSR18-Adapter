<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use WpOrg\Requests\Iri;
use Art4\Requests\Psr\Uri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetSchemeTest extends TestCase
{
    /**
     * Tests receiving the scheme when using getScheme().
     *
     * @covers \Art4\Requests\Psr\Uri::getScheme
     *
     * @return void
     */
    public function testGetScheme()
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->assertSame('https', $uri->getScheme());
    }

    /**
     * Tests receiving the scheme when using getScheme().
     *
     * @covers \Art4\Requests\Psr\Uri::getScheme
     *
     * @return void
     */
    public function testGetSchemeReturnEmptyString()
    {
        $uri = Uri::fromIri(new Iri('example.org'));

        $this->assertSame('', $uri->getScheme());
    }
}
