<?php
namespace Plista\Orp\KornakapiMatrixFactorization;
use Plista\Orp\Sdk\Handle;

/**
 * Class PushErrorHandler
 * @package Plista\Orp\KornakapiMatrixFactorization
 */
class PushErrorHandler implements Handle  {
	private static $path = '/var/www/log/';

	/**
	 * @param $error
	 * @return mixed|void
	 */
	public function handle($error) {

		// writing body informations to file
		$this->write_error($error);
	}

	/**
	 * @param $error
	 * @return bool
	 * @throws ValidationException
	 */
	public function validate($error) {
		if (empty($error)) {
			throw new ValidationException('Error: error_message is empty');
		}

		return true;
	}

	/**
	 * @param $error
	 * @throws Exception
	 */
	public function write_error($error) {
		$today = date("m.d.y");
		// writing errors in log file
		$res = file_put_contents('/var/www/log/error_' . $today . '.txt', serialize($error) . "\n", FILE_APPEND | LOCK_EX);
		if (!$res) {
			throw new Exception('Error: Unable to write to error file :(');
		}
	}

	/**
	 * @param $path
	 */
	public static function setPath($path) {
		self::$path = $path;
	}
}