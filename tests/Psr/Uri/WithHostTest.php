<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Uri;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use WpOrg\Requests\Iri;
use Art4\Requests\Psr\Uri;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class WithHostTest extends TestCase
{
    /**
     * Tests changing the host when using withHost().
     *
     * @covers \Art4\Requests\Psr\Uri::withHost
     *
     * @return void
     */
    public function testWithHostReturnsUri()
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->assertInstanceOf(UriInterface::class, $uri->withHost('example.com'));
    }

    /**
     * Tests changing the host when using withHost().
     *
     * @covers \Art4\Requests\Psr\Uri::withHost
     *
     * @return void
     */
    public function testWithHostWithEmptyStringRemovesTheHost()
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $uri = $uri->withHost('');

        $this->assertSame('', $uri->getHost());
    }

    /**
     * Tests changing the host when using withHost().
     *
     * @covers \Art4\Requests\Psr\Uri::withHost
     *
     * @return void
     */
    public function testWithHostReturnsNewInstance()
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->assertNotSame($uri, $uri->withHost('example.com'));
    }

    /**
     * Tests receiving an exception when the withHost() method received an invalid input type as `$host`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Uri::withHost
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    public function testWithHostWithoutStringThrowsInvalidArgumentException($input)
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withHost(): Argument #1 ($host) must be of type string', Uri::class));

        $uri = $uri->withHost($input);
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
     * Tests changing the host when using withHost().
     *
     * @covers \Art4\Requests\Psr\Uri::withHost
     *
     * @return void
     */
    public function testWithHostChangesTheHost()
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $uri = $uri->withHost('example.com');

        $this->assertSame('example.com', $uri->getHost());
    }
}
