<?php

namespace Plista\Orp\KornakapiMatrixFactorization;

use PDO;
use Plista\Orp\Sdk\Context;
use Plista\Orp\Kornakapi\Kornakapi;
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

	public function __construct(Context $context, $limit = 20) {
		$this->domainid = $context->getPublisher();
		$this->userid = $context->getUser_cookie();
		if (!isset($this->userid)) {
			$this->userid = 0; // if no userid then set to zero and check in fetch to do itembased recommendation
		}

		$this->domainid = $context->getPublisher();
		$this->limit = $limit;
	}

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

	public function getLimit() {
		return $this->limit;
	}

	public function getUserid() {
		return $this->userid;
	}

	public function getDomainid() {
		return $this->domainid;
	}

	public function getKornakapi_recommender() {
		return $this->kornakapi_recommender;
	}

	/**
	 * @return Kornakapi
	 */
	public function getWrite() {
		$api = new Kornakapi('http://localhost:8080/kornakapi/', 100);
		return $api;
	}

	/**
	 * @return Kornakapi
	 */
	public function getRead() {
		$api = new  Kornakapi('http://localhost:8080/kornakapi/', 100);
		return $api;
	}

	/**
	 * This method forces kornakapi to callculate recommendations
	 */
	public function push() {
		$api = $this->getWrite();

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
	 * geter for mysql db
	 * @return PDO
	 */
	private function getPDO() {
		return new PDO('mysql:host=localhost;dbname=kornakapi;charset=utf8', 'root', '');
	}

	/**
	 *
	 * chacks if userid exists in mysql database taste_preferences
	 * @param $userid
	 * @return bool
	 */
	public function userIndb($userid) {
		$sql = 'select item_id from taste_preferences where user_id =' . $userid;
		$stmt = $this->getPDO()->query($sql);
		$userIndb = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (empty($userIndb)) {
			return false;
		}

		return true;
	}

	/**
	 *
	 * checks if item exists in mysql database taste_preferences
	 * @param $itemid
	 * @return bool
	 */
	public function itemIndb($itemid) {
		$sql = 'select user_id from taste_preferences where item_id =' . $itemid;
		$stmt = $this->getPDO()->query($sql);
		$userIndb = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (empty($userIndb)) {
			return false;
		}

		return true;
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
		$userIndb = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (empty($userIndb)) {
			return false;
		}

		return true;
	}
}