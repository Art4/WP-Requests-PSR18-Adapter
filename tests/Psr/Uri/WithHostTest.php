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

final class WithHostTest extends TestCase
{
    /**
     * Tests changing the host when using withHost().
     *
     * @covers \Art4\Requests\Psr\Uri::withHost
     */
    public function testWithHostReturnsUri(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        TestCase::assertInstanceOf(UriInterface::class, $uri->withHost('example.com'));
    }

    /**
     * Tests changing the host when using withHost().
     *
     * @covers \Art4\Requests\Psr\Uri::withHost
     */
    public function testWithHostWithEmptyStringRemovesTheHost(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $uri = $uri->withHost('');

        TestCase::assertSame('', $uri->getHost());
    }

    /**
     * Tests changing the host when using withHost().
     *
     * @covers \Art4\Requests\Psr\Uri::withHost
     */
    public function testWithHostReturnsNewInstance(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        TestCase::assertNotSame($uri, $uri->withHost('example.com'));
    }

    /**
     * Tests receiving an exception when the withHost() method received an invalid input type as `$host`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Uri::withHost
     *
     * @param mixed $input Invalid parameter input.
     */
    #[DataProvider('dataInvalidTypeNotString')]
    public function testWithHostWithoutStringThrowsInvalidArgumentException($input): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withHost(): Argument #1 ($host) must be of type string', Uri::class));

        $uri->withHost($input);
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
     * Tests changing the host when using withHost().
     *
     * @covers \Art4\Requests\Psr\Uri::withHost
     */
    public function testWithHostChangesTheHost(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $uri = $uri->withHost('example.com');

        TestCase::assertSame('example.com', $uri->getHost());
    }
}
