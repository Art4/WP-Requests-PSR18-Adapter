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
     *
     * @return void
     */
    public function testWithFragmentReturnsUri()
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        TestCase::assertInstanceOf(UriInterface::class, $uri->withFragment('fragment'));
    }

    /**
     * Tests changing the fragment when using withFragment().
     *
     * @covers \Art4\Requests\Psr\Uri::withFragment
     *
     * @return void
     */
    public function testWithFragmentReturnsNewInstance()
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
     *
     * @return void
     */
    #[DataProvider('dataInvalidTypeNotString')]
    public function testWithFragmentWithoutStringThrowsInvalidArgumentException($input)
    {
        $uri = Uri::fromIri(new Iri('https://example.org'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::withFragment(): Argument #1 ($fragment) must be of type string', Uri::class));

        $uri = $uri->withFragment($input);
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
     * Tests changing the fragment when using withFragment().
     *
     * @dataProvider dataWithFragment
     *
     * @covers \Art4\Requests\Psr\Uri::withFragment
     *
     * @param string $input
     * @param string $expected
     *
     * @return void
     */
    #[DataProvider('dataWithFragment')]
    public function testWithFragmentChangesTheFragment($input, $expected)
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
    public static function dataWithFragment()
    {
        return [
            'Return an instance with the specified fragment string' => ['fragment', 'fragment'],
            'Users can provide encoded fragment characters' => ['frag#ment', 'frag%23ment'],
            'Users can provide decoded fragment characters' => ['frag%23ment', 'frag%23ment'],
            'An empty fragment value is equivalent to removing the fragment' => ['', ''],
        ];
    }
}
