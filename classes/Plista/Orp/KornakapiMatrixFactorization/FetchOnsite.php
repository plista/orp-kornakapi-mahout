<?php
namespace Plista\Orp\KornakapiMatrixFactorization;


/**
 * Class FetchOnsite, used for onsiterecommendations
 * @package Plista\Orp\orp-sdk-php\KornakapiMatrixFactorization
 */
class FetchOnsite extends Fetch  {

	/**
	 * @param int $limit
	 */
	public function fetchOnsite() {
		return $this->fetch();
	}
}