<?php
namespace Plista\Orp\KornakapiMatrixFactorization;

use Plista\Orp\Sdk\Handle;
use Plista\Orp\Sdk\Context;

/**
 * Class PushStatistic, this class updates the itemstatistics for the useres
 * @package Plista\Orp\orp-sdk-php\KornakapiMatrixFactorization
 */
class PushStatistic implements Handle {
	protected $supported_action = array('impression', 'click');

	private static $path = '/var/www/log/';

	/**
	 * @var int
	 */
	public $itemid = 0;

	/**
	 * optional
	 * @var int
	 */
	public $userid = 0;

	/**
	 * @var Model
	 */
	protected $model;


	/**
	 * @param $body
	 * @return bool
	 * @throws ValidationException
	 */
	public function validate($body) {
		// checking if body contains a notification type
		// additionally one is able to differentiate between a click, impression, engagement and cpo
		// for futher details may have a look at the controller gateway for notification types
		if (empty($body['type'])) {
			throw new ValidationException('Error: empty notification type');
		}

		if (empty($body['context'])) {
			throw new ValidationException('Error: there is no valid context provides in the body.');
		}

		return true;
	}


	/**
	 * This method adds a new item and user to taste_preferences
	 */
	public function push($rating) {


		$this->model->getRead()->setPreference(strval($this->userid), strval($this->itemid), $rating);

	}

	/**
	 * @param $body
	 * @return mixed
	 */
	public function handle($body) {
		$context = new Context($body['context']);
		$this->model = new Model($context);
		$this->userid = $this->idMapping($body['context']['simple']['57']);
		if($this->userid == 0){
			return;
		}

		if(isset($body['recs']['ints'][3][0]) && $body['recs']['ints'][3][0] != 0) {	//if click
			$this->itemid = $body['recs']['ints'][3][0];
			$this->push(0.7);
			$this->itemid = isset($body['context']['simple'][25]) ? $body['context']['simple'][25] : 0 ;
			if($this->itemid){

				$this->push(1);
			}

			if($context->getItem_source()){	//if impression
				$this->itemid =$context->getItem_source();
				if(!$this->model->itemuserIndb($this->itemid, $this->userid)){
					$this->push(0.7);
				}
			}
		}
		/**
		 * uncomment for PushStatistic log
		 */

//		$today = date("m.d.y");
//		$res = file_put_contents( self::$path.'PushStatistic_' . $today . '.log', serialize($body) . "\n", FILE_APPEND | LOCK_EX);
//
//		if (!$res) {
//			throw new Exception('Error: Unable to write to statistic file :(');
//		}
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
