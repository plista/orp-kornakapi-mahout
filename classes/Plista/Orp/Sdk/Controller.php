<?php
namespace Plista\Orp\Sdk;


/**
 * This class represents the controller for handling the different types of json data
 */
final class Controller {
	private static $supported_messages = array(
		'recommendation_request',
		'item_update',
		'event_notification',
		'error_notification'
	);

	/**
	 * @var Handle[]
	 */
	private $handler = array();

	/**
	 * subscription to the live statistics from hpt.
	 * @param string $type the type of the incoming message ($_POST['type'])
	 * @param string $body a json encoded message
	 * @throws ControllerException
	 */
	public function handle($type, $body) {

		// Checking if the type of the JSON is supported
		if (!in_array($type, self::$supported_messages)) {
			// if type is not supported, throw an exception
			throw new Exception ('Error: the type is not supported');
		}

		//if so, decode the json (work with arrays)
		$body = @json_decode($body, true);

		//if you use kornakapi, make shure to uncomment this condition
		if(array_key_exists('context',$body)){
			$body['context']['simple']['57']=$this->idMapping($body['context']['simple']['57']);
		}


		// catching unexcepted errors during the decoding of the json string
		// may want to enable if json_decode returns null
		/*
		$json_errors = array(
			JSON_ERROR_NONE => 'No error has occurred',
			JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
			JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
			JSON_ERROR_SYNTAX => 'Syntax error',
		);
		$res =  'Last error : '. $json_errors[json_last_error()]. PHP_EOL. PHP_EOL;
		echo $res;
		*/

		// we can only work with objects/associative arrays, so if the body is something else we don't want to continue
		if (!is_array($body)) {
			throw new Exception ('Error: body is not an array!');
		}

		// check whether a handler was installed
		if (empty($this->handler[$type])) {
			throw new Exception('Error: no handler registered for ' . $type);
		}

		$handler = $this->handler[$type];
		// validate the body data regarding the type based specifications
		$handler->validate($body);
		// handling the body data regarding the type based specifications
		return $handler->handle($body);

		// Gateway for notification types - optional
		/*
		// get notification type
		$notitype = $body->getType()->getValue();
		// differentiate between the specified notification types
		switch ($notitype) {
			case 'click':
				// call handler with notification type
				$handler->handle($body, $notitype);
				break;
			case 'impression':
				// call handler with notification type
				$handler->handle($body, $notitype);
				break;
			case 'engagement':
				// call handler with notification type
				$handler->handle($body, $notitype);
				break;
			case 'cpo':
				// call handler with notification type
				$handler->handle($body, $notitype);
				break;
		}
		*/
	}

	public function setHandler($method, Handle $object) {
		$this->handler[$method] = $object;
	}

	/**
	 * Plista user_id's can exceed the integer limit, unfortunately mahout's indexes are limited to integer, therefore we do this
	 * simple remapping
	 * @param $globalUserID
	 * @return int|number
	 */
	public function idMapping($globalUserID){
		if($globalUserID == 0){
			return 0;
		}
		return abs($globalUserID - 1000000000);
	}
}

