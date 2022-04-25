<?php

namespace WpOrg\Requests\Tests\Utility\InputValidator;

use WpOrg\Requests\Tests\TestCase;
use WpOrg\Requests\Tests\TypeProviderHelper;
use WpOrg\Requests\Utility\InputValidator;

/**
 * @covers \WpOrg\Requests\Utility\InputValidator::is_stringable_object
 */
final class IsStringableObjectTest extends TestCase {

	/**
	 * Test whether a received input parameter is correctly identified as "stringable".
	 *
	 * @dataProvider dataValid
	 *
	 * @param mixed $input Input parameter to verify.
	 *
	 * @return void
	 */
	public function testValid($input) {
		$this->assertTrue(InputValidator::is_stringable_object($input));
	}

	/**
	 * Data Provider.
	 *
	 * @return array
	 */
	public function dataValid() {
		return TypeProviderHelper::getSelection(['Stringable object']);
	}

	/**
	 * Test whether a received input parameter is correctly identified as NOT "stringable".
	 *
	 * @dataProvider dataInvalid
	 *
	 * @param mixed $input Input parameter to verify.
	 *
	 * @return void
	 */
	public function testInvalid($input) {
		$this->assertFalse(InputValidator::is_stringable_object($input));
	}

	/**
	 * Data Provider.
	 *
	 * @return array
	 */
	public function dataInvalid() {
		return TypeProviderHelper::getAllExcept(['Stringable object']);
	}
}
