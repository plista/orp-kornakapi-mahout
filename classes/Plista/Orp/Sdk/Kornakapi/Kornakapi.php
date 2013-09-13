<?php
namespace Plista\Orp\Sdk\Kornakapi;

/**
 * access to kornakapi aka mahout http interface
 */
class Kornakapi {

	/**
	 * @var Http\Http
	 */
	protected $http;

	/**
	 * Constructor
	 * @param string $url
	 * @param float $timeout_default
	 * @param array $timeout_config
	 */
	public function __construct($url, $timeout_default = 1.0, $timeout_config = array()) {
		$this->http = new Http\Http($url, $timeout_default, $timeout_config);
	}

	/**
	 * add item to a labeled candidate set
	 * @param string $label candidate set in which we want to label the specific itemID. A empty label is also possible.
	 * @param int $itemID itemID
	 */
	public function addCandidate($label, $itemID) {
		$this->http->void('addCandidate', array(
			'label'  => $label,
			'itemID' => $itemID
		));
	}

	/**
	 * add items to labeled candidate sets.
	 * @param array $data tuples with label as 1st and itemid as 2nd element
	 * e.g.: array(
	 *  array('label1', 111),
	 *  array('label2', 111),
	 *  array('label1', 222)
	 * )
	 * @param int $batchsize size of the batch
	 * @throws Exception
	 */
	public function batchAddCandidates(array $data, $batchsize) {
		$this->http->batch('batchAddCandidates', $data, $batchsize);
	}

	/**
	 * remove items from labeled candidate sets.
	 * @param array $data tuples with label as 1st and itemid as 2nd element
	 * e.g.: array(
	 *  array('label1', 111),
	 *  array('label2', 111),
	 *  array('label1', 222)
	 * )
	 * @param int $batchsize size of the batch
	 * @throws Exception
	 */
	public function batchDeleteCandidates(array $data, $batchsize) {
		$this->http->batch('batchDeleteCandidates', $data, $batchsize);
	}

	/**
	 * delete item from a labeled candidate set
	 * @param string $label candidate set. A empty label is also possible (it it exist).
	 * @param int $itemID itemID which we want to delete from the candidate set
	 */
	public function deleteCandidate($label, $itemID) {
		$this->http->void('deleteCandidate', array(
			'label'  => $label,
			'itemID' => $itemID
		));
	}

	/**
	 * delete a entire candidate set
	 * @param string $label name of the label which will be deleted
	 */
	public function deleteCandidateFull($label) {
		$this->http->void('deleteCandidateFull', array(
			'label' => $label
		));
	}

	/**
	 * add a single preference
	 * @param int $userID ID of the user
	 * @param int $itemID ID of the item
	 * @param int $value value of the preference
	 */
	public function setPreference($userID, $itemID, $value) {
		$this->http->void('setPreference', array(
			'userID' => $userID,
			'itemID' => $itemID,
			'value'  => $value
		));
	}

	/**
	 * add preferences.
	 * @param array $data tuples with userid as 1st and itemid as 2nd element
	 * e.g.: array(
	 *  array(111, 50001),
	 *  array(111, 50002),
	 *  array(111, 50003)
	 * )
	 * @param int $batchsize size of the batch
	 * @throws Exception
	 */
	public function batchSetPreferences(array $data, $batchsize) {
		$this->http->batch('batchSetPreferences', $data, $batchsize);
	}

	/**
	 * Making a request on the recommend-Servlet and getting back the founded items
	 * @param string $recommender recommender-types which can have the following values:
	 *         itembased...computing similarities between the items based on the way they were rated
	 *         weighted-mf... using mathematical techniques (matrix factorization) to find highly preferrable items
	 * @param string $idType Type of ID:
	 *        userID...user
	 *        itemIDs...anonymous user
	 * @param array $id ID of the user (userID) or ID's for the anonymous user (itemIDs)
	 * @param string $label optional; choosed candidate set on which we search the items. If label won't found in kornakapi this parameter will be ignored
	 *                         A empty label is also possible (if it exist)
	 * @param int $howMany optional; how many found items will returned. If this parameter isn't denoted all found items will returned.
	 * @throws Exception
	 * @return array with array object that contain itemID and numeric score as value e.g. [{itemID:557,value:5.988698},{itemID:578,value:5.0461025}, ..]
	 */
	public function recommend($recommender, $idType, array $id, $label = '', $howMany = 20) {

		if (empty($id)) {
			throw new Exception('id is empty');
		}

		$params = array(
			'recommender' => $recommender
		);

		if ($idType == 'userID') {
			$params['userID'] = implode(',', $id);

		} else if ($idType == 'itemIDs') {
			$params['itemIDs'] = implode(',', $id);
		} else {
			throw new Exception('wrong idType: ' . $idType . ' value should either be userID oder itemIDs');
		}

		// limit results
		if (!empty($howMany)) {
			$params['howMany'] = intval($howMany);
		}

		// filter candidate label
		if (!empty($label)) {
			$params['label'] = strval($label);
		}

		$result = $this->http->fetch('recommend', $params);

		//error_log("result: " . $result);
		if ($result) {

			//PHP json_decode workaround
			$result = str_replace(array("itemID", "value"), array('"itemID"', '"value"'), $result);


			return json_decode($result, true);
		} else {
			throw new Exception('no results given, recommender ' . $recommender . ' may be unknown');
		}
	}

	/**
	 * manually trigger the training for a recommender
	 * @param string $recommender name of the recommender which should be trained
	 */
	public function train($recommender) {
		$this->http->void('train', array(
			'recommender' => $recommender
		));
	}
}
