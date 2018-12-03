<?php
namespace PCronManager;

class Config {

	public static function  get ($name) {
		$container = Container::instance();
		$config = $container->get('config');
		if (empty($config)) {
			self::setConfig();
		}
		
		return $config[$name];
	}

	public static function setConfig () {
		$container = Container::instance();
		$config = require INSTALL_PATH . '/config.php';
		$container->set('config', $config);
	} 

}