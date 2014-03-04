<?php

namespace Plista\Orp\KornakapiMatrixFactorization;

use Org\Plista\Kornakapi\Kornakapi;
use PDO;
use Plista\Orp\Sdk\Context;
use Plista\Orp\Sdk\ValidationException;

/**
 * Class Model, this class handles some additional io operations
 * @package Plista\Orp\KornakapiMatrixFactorization
 */
class Model {

	private $userid;
	private $limit;
	private $domainid;
	private $kornakapi_recommender = 'weighted-mf';

	/**
	 * @param Context $context
	 * @param int $limit
	 */
	public function __construct(Context $context, $limit = 20) {
		$this->domainid = $context->getPublisher();
		$this->userid = $this->idMapping($context->getUser_cookie());
		if (!isset($this->userid)) {
			$this->userid = 0; // if no userid then set to zero and check in fetch to do itembased recommendation
		}

		$this->limit = $limit;
	}

	/**
	 * @throws \Plista\Orp\Sdk\ValidationException
	 */
	public function validate() {
		if (!isset($this->userid)) {
			throw new ValidationException('Userid has to be given.' . strval($this->userid));
		}

		if (empty($this->domainid)) {
			throw new ValidationException('Domain id has to be given.');
		}

		if (empty($this->limit)) {
			throw new ValidationException('Limit has to be given.');
		}
	}

	/**
	 * @return int
	 */
	public function getLimit() {
		return $this->limit;
	}

	/**
	 * @return int
	 */
	public function getUserid() {
		return $this->userid;
	}

	/**
	 * @return int
	 */
	public function getDomainid() {
		return $this->domainid;
	}

	/**
	 * @return string
	 */
	public function getKornakapi_recommender() {
		return $this->kornakapi_recommender;
	}

	/**
	 * @return Kornakapi
	 */
	public function getRead() {
		$api = new  Kornakapi('http://localhost:8080/kornakapi/', 10000);
		return $api;
	}

	/**
	 * This method forces kornakapi to callculate recommendations
	 */
	public function push() {
		$api = $this->getRead();

		try {
			$api->train($this->getKornakapi_recommender());
		} catch (\Exception $e) {
			print('train not succesfull');
		}
	}

	/**
	 * @return int
	 */
	public function getLabel() {
		return $this->domainid;
	}

	/**
	 * geter for mysql db, check if db name, user (root) and pw are set properly
	 * @return PDO
	 */
	private function getPDO() {
		return new PDO('mysql:host=localhost;dbname=kornakapi;charset=utf8', 'root', '');
	}

	/**
	 *
	 * checks if userid exists in mysql database taste_preferences
	 * @param $userid
	 * @return bool
	 */
	public function userIndb($userid) {
		$sql = ' select exists(select 1 from taste_preferences where user_id = '. $userid .' limit 1)' ;
		$stmt = $this->getPDO()->query($sql);
		$userIndb = $stmt->fetch(PDO::FETCH_ASSOC);
		return array_pop($userIndb);
	}

	/**
	 *
	 * checks if item exists in mysql database taste_preferences
	 * @param $itemid
	 * @return bool
	 */
	public function itemIndb($itemid) {
		$sql = 'select exists(select 1 from taste_preferences where item_id = '. $itemid .' limit 1)';
		$stmt = $this->getPDO()->query($sql);
		$itemIndb = $stmt->fetch(PDO::FETCH_ASSOC);
		return array_pop($itemIndb);
	}

	/**
	 * checks if an entry exists in taste_candidates for item and label
	 * @param $itemid
	 * @param $label
	 * @return bool
	 */
	public function itemlabelIndb($itemid, $label) {
		$sql = 'select item_id from taste_candidates where item_id =' . $itemid . '&& label =' . $label;
		$stmt = $this->getPDO()->query($sql);
		$userIndb = $stmt->fetch(PDO::FETCH_ASSOC);

		if (empty($userIndb)) {
			return false;
		}

		return true;
	}

	/**
	 * @param $item
	 * @param $user
	 * @return mixed
	 */
	public function itemuserIndb($item, $user){
		$sql = 'select exists(select 1 from taste_preferences where item_id = '. $item .' && user_id = '. $user .' limit 1)';
		$stmt = $this->getPDO()->query($sql);
		$itemIndb = $stmt->fetch(PDO::FETCH_ASSOC);
		return array_pop($itemIndb);
	}


	/**
	 * Plista user_id's can exceed the integer limit, unfortunately mahout's indexes are limited to integer, therefore we do this
	 * simple remapping
	 * @param $globalUserID
	 * @return int|number
	 */
	public function idMapping($globalUserID){
		return abs($globalUserID % 2147483647);
	}

}
