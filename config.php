<?php
require_once(__DIR__ . '/classes/SplClassLoader.php');
$classLoader = new SplClassLoader('Plista\\Orp', __DIR__ . '/classes');
$classLoader->register(true);
unset($classLoader);
require_once __DIR__ . '/../orp-sdk-php/config.php';
require_once __DIR__ . '/../kornakapi-php/autoload.php';
