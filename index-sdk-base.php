<?php

$type = $_POST['type'];
$body = $_POST['body'];

$controller = new \Plista\Orp\Sdk\Controller();
$controller->handle($type, $body);
