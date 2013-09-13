<?php
namespace Plista\Orp\Sdk\Kornakapi\Http;

/**
 * kornakapi timeout configuration
 */
class Timeout {

	/**
	 * @param float $default
	 * @param array $config
	 */
	public function __construct($default, array $config) {
		$this->default = $default;
		$this->config = $config;
	}

	/**
	 * @param string $key
	 * @return float
	 */
	public function get($key) {
		$res = isset($this->config[$key]) ? $this->config[$key] : $this->default;
		return floatval($res);
	}

}
