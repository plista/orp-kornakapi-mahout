<?php
namespace Plista\Orp\KornakapiMatrixFactorization;
use Plista\Orp\Sdk\Handle;

class PushErrorHandler implements Handle  {
	private static $path = '';

	public function handle($error) {
		/**
		 * @var Model $model
		 */
		// writing body informations to file
		$this->write_error($error);
	}

	public function validate($error) {
		if (empty($error)) {
			throw new ValidationException('Error: error_message is empty');
		}

		return true;
	}

	public function write_error($error) {
		$today = date("m.d.y");
		// writing errors in log file
		$res = file_put_contents('error_' . $today . '.txt', serialize($error) . "\n", FILE_APPEND | LOCK_EX);
		if (!$res) {
			throw new Exception('Error: Unable to write to error file :(');
		}
	}
	public static function setPath($path) {
		self::$path = $path;
	}
}