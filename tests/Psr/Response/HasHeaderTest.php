<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Psr\Response;

use InvalidArgumentException;
use Art4\Requests\Psr\Response;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class HasHeaderTest extends TestCase
{
    /**
     * Tests receiving boolean when using hasHeader().
     *
     * @covers \Art4\Requests\Psr\Response::hasHeader
     *
     * @return void
     */
    public function testHasHeaderReturnsFalse()
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->assertFalse($response->hasHeader('name'));
    }

    /**
     * Tests receiving boolean when using hasHeader().
     *
     * @covers \Art4\Requests\Psr\Response::hasHeader
     *
     * @return void
     */
    public function testHasHeaderReturnsTrue()
    {
        $response = Response::fromResponse(new RequestsResponse());
        $response = $response->withHeader('name', 'value');

        $this->assertTrue($response->hasHeader('name'));
    }

    /**
     * Tests receiving boolean when using hasHeader().
     *
     * @covers \Art4\Requests\Psr\Response::hasHeader
     *
     * @return void
     */
    public function testHasHeaderWithCaseInsensitiveNameReturnsTrue()
    {
        $response = Response::fromResponse(new RequestsResponse());
        $response = $response->withHeader('NAME', 'value');

        $this->assertTrue($response->hasHeader('name'));
    }

    /**
     * Tests receiving an exception when the hasHeader() method received an invalid input type as `$name`.
     *
     * @dataProvider dataInvalidTypeNotString
     *
     * @covers \Art4\Requests\Psr\Response::hasHeader
     *
     * @param mixed $input Invalid parameter input.
     *
     * @return void
     */
    public function testHasHeaderWithoutStringThrowsInvalidArgumentException($input)
    {
        $response = Response::fromResponse(new RequestsResponse());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s::hasHeader(): Argument #1 ($name) must be of type string,', Response::class));

        $response->hasHeader($input);
    }

    /**
     * Data Provider.
     *
     * @return array
     */
    public function dataInvalidTypeNotString()
    {
        return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_STRING);
    }
}
