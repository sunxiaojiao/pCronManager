<?php
ini_set('date.timezone','Asia/Shanghai');

if (substr(php_sapi_name(), 0, 3) !== 'cli') {
	exit('Not Cli');
}

set_time_limit(0);


require  INSTALL_PATH . '/vendor/autoload.php';


$container = PCronManager\Container::instance();
// config
PCronManager\Config::setConfig();
