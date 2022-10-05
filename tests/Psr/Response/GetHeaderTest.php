<?php

namespace Art4\Requests\Tests\Psr\Response;

use InvalidArgumentException;
use Art4\Requests\Psr\Response;
use WpOrg\Requests\Response as RequestsResponse;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Art4\Requests\Tests\TypeProviderHelper;

final class GetHeaderTest extends TestCase {

	/**
	 * Tests receiving the header when using getHeader().
	 *
	 * @covers \Art4\Requests\Psr\Response::getHeader
	 *
	 * @return void
	 */
	public function testGetHeaderWithoutHeaderReturnsEmptyArray() {
		$response = Response::fromResponse(new RequestsResponse());

		$this->assertSame([], $response->getHeader('name'));
	}

	/**
	 * Tests receiving the header when using getHeader().
	 *
	 * @covers \Art4\Requests\Psr\Response::getHeader
	 *
	 * @return void
	 */
	public function testGetHeaderReturnsArray() {
		$response = Response::fromResponse(new RequestsResponse());
		$response = $response->withHeader('name', 'value');

		$this->assertSame(['value'], $response->getHeader('name'));
	}

	/**
	 * Tests receiving the header when using getHeader().
	 *
	 * @covers \Art4\Requests\Psr\Response::getHeader
	 *
	 * @return void
	 */
	public function testGetHeaderWithCaseInsensitiveNameReturnsArray() {
		$response = Response::fromResponse(new RequestsResponse());
		$response = $response->withHeader('name', 'value');

		$this->assertSame(['value'], $response->getHeader('NAME'));
	}

	/**
	 * Tests receiving an exception when the getHeader() method received an invalid input type as `$name`.
	 *
	 * @dataProvider dataInvalidTypeNotString
	 *
	 * @covers \Art4\Requests\Psr\Response::getHeader
	 *
	 * @param mixed $input Invalid parameter input.
	 *
	 * @return void
	 */
	public function testGetHeaderWithoutStringThrowsInvalidArgumentException($input) {
		$response = Response::fromResponse(new RequestsResponse());

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage(sprintf('%s::getHeader(): Argument #1 ($name) must be of type string,', Response::class));

		$response->getHeader($input);
	}

	/**
	 * Data Provider.
	 *
	 * @return array
	 */
	public function dataInvalidTypeNotString() {
		return TypeProviderHelper::getAllExcept(TypeProviderHelper::GROUP_STRING);
	}
}
