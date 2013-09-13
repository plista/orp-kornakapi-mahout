<?php
namespace Plista\Orp\Sdk;


use Plista\Orp\Sdk\Example\Exception;

class VectorSequence {

	/**
	 * @var string
	 */
	public $type;
	/**
	 * @var array
	 */
	public $context = array();
	/**
	 * @var array
	 */
	public $recs = array();
	/**
	 * @var string
	 */
	public $timestamp;

	/**
	 * @param string $str
	 * @throws Exception when an error occurred during json processing
	 * @return \Plista\Orp\Sdk\VectorSequence
	 */
	public static function fromJson($str) {

		$data = @json_decode($str, true);

		if ($data === false) {
			throw new Exception('Could not decode JSON: ' . json_last_error());
		}

		if (empty($data['type'])) {
			throw new Exception('type missing in JSON');
		}

		if (empty($data['context'])) {
			throw new Exception('context missing in JSON');
		}

		if (empty($data['timestamp'])) {
			throw new Exception('timestamp missing in JSON');
		}

		$instance = new self();
		$instance->setType($data['type']);
		$instance->setContext($data['context']);
		$instance->setRecs($data['recs']);
		$instance->setTimestamp($data['timestamp']);

		return $instance;
	}

	/**
	 * @param $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @param $context
	 */
	public function setContext($context) {
		$this->context = $context;
	}

	/**
	 * @param $recs
	 */
	public function setRecs($recs) {
		$this->recs = $recs;
	}

	/**
	 * @param $timestamp
	 */
	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}

	/**
	 * @return Type
	 */
	public function getType() {
		return new Type($this->type);
	}

	/**
	 * @return Context
	 */
	public function getContext() {
		return new Context($this->context);
	}

	/**
	 * @return Recs
	 */
	public function getRecs() {
		return new Recs($this->recs);
	}

	/**
	 * @return Timestamp
	 */
	public function getTimestamp() {
		return new Timestamp($this->timestamp);
	}
}