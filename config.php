<?php

require_once(__DIR__ . '/classes/SplClassLoader.php');

$classLoader = new SplClassLoader('Plista\\Orp', __DIR__ . '/classes');
$classLoader->register(true);
