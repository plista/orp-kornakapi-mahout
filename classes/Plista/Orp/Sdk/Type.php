<?php
namespace Plista\Orp\Sdk;

class Type {

	/**
	 * @var string
	 */
	private $data;

	public function __construct($data) {

		if (empty($data)) {
			throw new Exception('Error: provided data array is empty');
		}

		$this->data = $data;
	}

	/**
	 * possible values are [impression, click, ...]
	 * @return string
	 */
	public function getValue() {
		return $this->data;
	}
}