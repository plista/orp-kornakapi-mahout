<?php
namespace Plista\Orp\KornakapiMatrixFactorization;

use Plista\Orp\Sdk\Handle;
use Plista\Orp\Sdk\ValidationException;
use Plista\Orp\Sdk\Context;

/**
 * Class PushItem, this class adds new items to the pool, we use this in order to filter recommendations per domain
 * @package Plista\Orp\Sdk\KornakapiMatrixFactorization
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

	/**
	 * subscription to item updates
	 *
	 */


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

	public function push() {

		if(!$this->model->itemlabelIndb($this->itemid,$this->label)){
			$this->model->getRead()->addCandidate($this->label, strval($this->itemid));
		}
	}

	/**
	 * @param $body
	 * @return mixed
	 */
	public function handle($body) {
//		Context $context, $params

		$data[Context::ITEM_SOURCE]= $body['id'];
		$data[Context::PUBLISHER]= $body['domainid'];
		$data[Context::USER_COOKIE]= 0;

		$context = new Context(array('simple'=>$data));
		$this->model = new Model($context);

		$this->label = strval($body['domainid']);
		$this->itemid = $body['id'];
		$this->push();

	}
}