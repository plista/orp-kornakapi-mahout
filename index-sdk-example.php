<?php
require_once(__DIR__ . '/config.php');


use Plista\Orp\KornakapiMatrixFactorization\PushItem;
use Plista\Orp\KornakapiMatrixFactorization\FetchOnsite;
use Plista\Orp\KornakapiMatrixFactorization\PushErrorHandler;
use Plista\Orp\KornakapiMatrixFactorization\PushStatistic;


// define path
PushErrorHandler::setPath(__DIR__ . '/logs/');

// defining controller
$controller = new \Plista\Orp\Sdk\Controller();

//defining the handles
$handleItem = new PushItem();
$handleRequest = new FetchOnsite();
$handleError = new PushErrorHandler();
$handleNotify = new PushStatistic();

// assigning type to handle
$controller->setHandler('item_update', $handleItem);
$controller->setHandler('recommendation_request', $handleRequest);
$controller->setHandler('event_notification', $handleNotify);
$controller->setHandler('error_notification', $handleError);

// checking if either body or type is empty
if (empty($_POST['body'])) {
	throw new \Exception ('Warning: body is empty :(');
}

if (empty($_POST['type'])) {
	throw new \Exception ('Warning: type is empty :(');
}




		// collecting type and body
$type = $_POST['type'];
$body = $_POST['body'];
//file_put_contents('Incomming.log', date("c"). " " . $_SERVER["REMOTE_ADDR"]. " " . $type .  "\n", FILE_APPEND);



	// calling controller to handle incoming messages
	$result = $controller->handle($type, $body);

	// if the request was a recommendation request and we got results, print them out
	if ($result instanceof \Plista\Orp\Sdk\Recs) {
		$res = $result->toJSON();
		$log = file_put_contents( 'Outgoing.log', $res . "\n", FILE_APPEND | LOCK_EX);

		if (!$log) {
			throw new Exception('Error: Unable to write to statistic file :(');
		}
		echo $res;
	}

