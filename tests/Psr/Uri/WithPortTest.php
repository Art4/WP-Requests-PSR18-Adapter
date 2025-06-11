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

final class WithPortTest extends TestCase
{
    /**
     * Tests changing the port when using withPort().
     *
     * @covers \Art4\Requests\Psr\Uri::withPort
     */
    public function testWithPortReturnsUri(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        TestCase::assertInstanceOf(UriInterface::class, $uri->withPort(5000));
    }

    /**
     * Tests changing the port when using withPort().
     *
     * @covers \Art4\Requests\Psr\Uri::withPort
     */
    public function testWithPortWithNullRemovesThePort(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org:5000'));

        $uri = $uri->withPort(null);

        TestCase::assertSame(null, $uri->getPort());
    }

    /**
     * Tests changing the port when using withPort().
     *
     * @covers \Art4\Requests\Psr\Uri::withPort
     */
    public function testWithPortReturnsNewInstance(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        TestCase::assertNotSame($uri, $uri->withPort(5000));
    }

    /**
     * Tests receiving an exception when the withPort() method received an invalid input type as `$port`.
     *
     * @dataProvider dataInvalidTypeNotIntOrNull
     *
     * @covers \Art4\Requests\Psr\Uri::withPort
     *
     * @param mixed $input Invalid parameter input.
     */
    #[DataProvider('dataInvalidTypeNotIntOrNull')]
    public function testWithPortWithoutIntOrNullThrowsInvalidArgumentException($input): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withPort(): Argument #1 ($port) must be of type null|int', Uri::class));

        $uri->withPort($input);
    }

    /**
     * Data Provider.
     *
     * @return array<string, mixed>
     */
    public static function dataInvalidTypeNotIntOrNull()
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_INT, TypeProviderHelper::GROUP_NULL);
    }

    /**
     * Tests changing the port when using withPort().
     *
     * @covers \Art4\Requests\Psr\Uri::withPort
     */
    public function testWithPortChangesThePort(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $uri = $uri->withPort(5000);

        TestCase::assertSame(5000, $uri->getPort());
    }

    /**
     * Tests receiving an exception when the withPort() method received a port outside the
     * established TCP and UDP port ranges as `$port`.
     *
     * @dataProvider dataInvalidPorts
     *
     * @covers \Art4\Requests\Psr\Uri::withPort
     *
     * @param mixed $input Invalid parameter input.
     */
    #[DataProvider('dataInvalidPorts')]
    public function testWithPortWithoutValidPortThrowsInvalidArgumentException($input): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withPort(): Argument #1 ($port) must be of type null|int in the range of 0 - 65535', Uri::class));

        $uri->withPort($input);
    }

    /**
     * Data Provider.
     *
     * @return array<string,array<int>>
     */
    public static function dataInvalidPorts(): array
    {
        return [
            'negative integer' => [-1],
            'bigger than 65535' => [65536],
        ];
    }
}
