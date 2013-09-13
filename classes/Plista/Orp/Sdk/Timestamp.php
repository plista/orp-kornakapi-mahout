<?php
namespace Plista\Orp\Sdk;

class Timestamp {

	private $data = array();

	public function __construct($data) {

		if (empty($data)) {
			throw new Exception('Error: provided data array is empty');
		}


		$this->data = $data;
	}

	public function getTimestampValue() {
		return $this->data;
	}
}
