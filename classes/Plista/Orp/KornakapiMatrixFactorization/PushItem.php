<?php
namespace Plista\Orp\KornakapiMatrixFactorization;

use Plista\Orp\Sdk\Handle;
use Plista\Orp\Sdk\ValidationException;
use Plista\Orp\Sdk\Context;

/**
 * Class PushItem, this class adds new items to the pool, we use this in order to filter recommendations per domain
 * @package Plista\Orp\orp-sdk-php\KornakapiMatrixFactorization
 */
class PushItem implements Handle {


	private $label;

	/**
	 * @var Model
	 */
	private $model;

	/**
	 * @var int
	 */
	private $itemid;

	public function __construct() {


	}

	public function validate($item) {
		if (empty($item)) {
			throw new ValidationException('Error: item is empty');
		}

		if (empty($item['id'])) {
			throw new ValidationException('Error: Item ID is empty');
		}

		if (empty($item['domainid'])) {
			throw new ValidationException('Error: Domain ID is empty');
		}

		return true;
	}

	/**
	 * push item notifications to kornakapi
	 */
	public function push() {

		if(!$this->model->itemlabelIndb($this->itemid,$this->label)){
			$this->model->getRead()->addCandidate($this->label, strval($this->itemid));
		}
	}

	public function invalidateItem(){
		if($this->model->itemlabelIndb($this->itemid,$this->label)){
			$this->model->getRead()->deleteCandidate($this->label,$this->itemid);
		}
	}

	/**
	 * @param $body
	 * @return mixed
	 */
	public function handle($body) {


		$data[Context::ITEM_SOURCE]= $body['id'];
		$data[Context::PUBLISHER]= $body['domainid'];
		$data[Context::USER_COOKIE]= 0;		//quickfix, User_id is not required for new item notifications, but read out in the model constructor

		$context = new Context(array('simple'=>$data));
		$this->model = new Model($context);

		$this->label = strval($body['domainid']);
		$this->itemid = $body['id'];


		if($body['flag']== 0){ //check for invalidation flag
			$this->push();
		}else{
			$this->invalidateItem();
		}
	}
}