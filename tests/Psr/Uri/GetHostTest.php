<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use Art4\Requests\Psr\Uri;
use WpOrg\Requests\Iri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class GetHostTest extends TestCase
{
    /**
     * Tests receiving the host when using getHost().
     *
     * @covers \Art4\Requests\Psr\Uri::getHost
     *
     * @return void
     */
    public function testGetHost()
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        TestCase::assertSame('example.org', $uri->getHost());
    }

    /**
     * Tests receiving the host when using getHost().
     *
     * @covers \Art4\Requests\Psr\Uri::getHost
     *
     * @return void
     */
    public function testGetHostReturnEmptyString()
    {
        $uri = Uri::fromIri(new Iri(''));

        TestCase::assertSame('', $uri->getHost());
    }

    /**
     * Tests receiving the host when using getHost().
     *
     * @covers \Art4\Requests\Psr\Uri::getHost
     *
     * @return void
     */
    public function testGetHostReturnLowercaseString()
    {
        $uri = Uri::fromIri(new Iri('https://EXAMPLE.ORG'));

        TestCase::assertSame('example.org', $uri->getHost());
    }
}
