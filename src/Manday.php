<?php

// Includes this file will load Manday autoloader

require 'Autoloader.php';

$mandayAutoloader = new Manday\Autoloader();
$mandayAutoloader->register()->add('Manday', __DIR__);
