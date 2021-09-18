<?php
/**
 * Case-insensitive dictionary, suitable for HTTP headers
 *
 * @package Requests
 * @subpackage Utilities
 */

namespace WpOrg\Requests\Utility;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use ReturnTypeWillChange;
use WpOrg\Requests\Exception;

/**
 * Case-insensitive dictionary, suitable for HTTP headers
 *
 * @package Requests
 * @subpackage Utilities
 */
class CaseInsensitiveDictionary implements ArrayAccess, IteratorAggregate {
	/**
	 * Actual item data
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Creates a case insensitive dictionary.
	 *
	 * @param array $data Dictionary/map to convert to case-insensitive
	 */
	public function __construct(array $data = array()) {
		foreach ($data as $key => $value) {
			$this->offsetSet($key, $value);
		}
	}

	/**
	 * Check if the given item exists
	 *
	 * @param string $offset Item key
	 * @return boolean Does the item exist?
	 */
	#[ReturnTypeWillChange]
	public function offsetExists($offset) {
		$offset = strtolower($offset);
		return isset($this->data[$offset]);
	}

	/**
	 * Get the value for the item
	 *
	 * @param string $offset Item key
	 * @return string|null Item value (null if offsetExists is false)
	 */
	#[ReturnTypeWillChange]
	public function offsetGet($offset) {
		$offset = strtolower($offset);
		if (!isset($this->data[$offset])) {
			return null;
		}

		return $this->data[$offset];
	}

	/**
	 * Set the given item
	 *
	 * @throws \WpOrg\Requests\Exception On attempting to use dictionary as list (`invalidset`)
	 *
	 * @param string $offset Item name
	 * @param string $value Item value
	 */
	#[ReturnTypeWillChange]
	public function offsetSet($offset, $value) {
		if ($offset === null) {
			throw new Exception('Object is a dictionary, not a list', 'invalidset');
		}

		$offset              = strtolower($offset);
		$this->data[$offset] = $value;
	}

	/**
	 * Unset the given header
	 *
	 * @param string $offset
	 */
	#[ReturnTypeWillChange]
	public function offsetUnset($offset) {
		unset($this->data[strtolower($offset)]);
	}

	/**
	 * Get an iterator for the data
	 *
	 * @return \ArrayIterator
	 */
	#[ReturnTypeWillChange]
	public function getIterator() {
		return new ArrayIterator($this->data);
	}

	/**
	 * Get the headers as an array
	 *
	 * @return array Header data
	 */
	public function getAll() {
		return $this->data;
	}
}
