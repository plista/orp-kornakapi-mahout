<?php
namespace Plista\Orp\Sdk\KornakapiMatrixFactorization;

use Plista\Orp\Sdk\Handle;
use Plista\Orp\Sdk\Context;

/**
 * Class PushStatistic, this class updates the itemstatistics for the useres
 * @package Plista\Orp\Sdk\KornakapiMatrixFactorization
 */
class PushStatistic implements Handle {
	protected $supported_action = array('impression', 'click');

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
	public function push() {


		$this->model->getWrite()->setPreference(strval($this->userid), strval($this->itemid), 1);

	}

	/**
	 * @param $body
	 * @return mixed
	 */
	public function handle($body) {
		$context = new Context($body['context']);
		$this->model = new Model($context);
		$this->userid = $body['context']['simple']['57'];

		if(isset($body['recs']['ints'][3][0])){	//if click
			$this->itemid = $body['recs']['ints'][3][0];
			$this->push();
		}
		if($context->getItem_source()){	//if impression
			$this->itemid =$context->getItem_source();
			$this->push();
		}


//
//		$today = date("m.d.y");
//		$res = file_put_contents( 'PushStatistic_' . $today . '.log', serialize($body) . "\n", FILE_APPEND | LOCK_EX);
//
//		if (!$res) {
//			throw new Exception('Error: Unable to write to statistic file :(');
//		}



	}
}