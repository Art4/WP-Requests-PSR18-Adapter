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

final class WithFragmentTest extends TestCase
{
    /**
     * Tests changing the fragment when using withFragment().
     *
     * @covers \Art4\Requests\Psr\Uri::withFragment
     */
    public function testWithFragmentReturnsUri(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        TestCase::assertInstanceOf(UriInterface::class, $uri->withFragment('fragment'));
    }

    /**
     * Tests changing the fragment when using withFragment().
     *
     * @covers \Art4\Requests\Psr\Uri::withFragment
     */
    public function testWithFragmentReturnsNewInstance(): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        TestCase::assertNotSame($uri, $uri->withFragment('fragment'));
    }

    /**
     * Tests receiving an exception when the withFragment() method received an invalid input type as `$fragment`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Uri::withFragment
     *
     * @param mixed $input Invalid parameter input.
     */
    #[DataProvider('dataInvalidTypeNotString')]
    public function testWithFragmentWithoutStringThrowsInvalidArgumentException($input): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withFragment(): Argument #1 ($fragment) must be of type string', Uri::class));

        $uri->withFragment($input);
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
     * Tests changing the fragment when using withFragment().
     *
     * @dataProvider dataWithFragment
     *
     * @covers \Art4\Requests\Psr\Uri::withFragment
     *
     * @param string $input
     * @param string $expected
     */
    #[DataProvider('dataWithFragment')]
    public function testWithFragmentChangesTheFragment($input, $expected): void
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $uri = $uri->withFragment($input);

        TestCase::assertSame($expected, $uri->getFragment());
    }

    /**
     * Data Provider.
     *
     * @return array<string,string[]>
     */
    public static function dataWithFragment(): array
    {
        return [
            'Return an instance with the specified fragment string' => ['fragment', 'fragment'],
            'Users can provide encoded fragment characters' => ['frag#ment', 'frag%23ment'],
            'Users can provide decoded fragment characters' => ['frag%23ment', 'frag%23ment'],
            'An empty fragment value is equivalent to removing the fragment' => ['', ''],
        ];
    }
}
