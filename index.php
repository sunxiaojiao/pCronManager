<?php

define('INSTALL_PATH', dirname(__FILE__));
define('SRC_PATH', INSTALL_PATH .  '/src');

require SRC_PATH . '/boot.php';

$scheduler = new \PCronManager\Scheduler();

$scheduler->run();