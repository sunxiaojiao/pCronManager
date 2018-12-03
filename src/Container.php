<?php
namespace PCronManager;
class Container {

	private static $map = [];

	private static $instance;

	private function __construct() {}

	public static function instance() {
		if (self::$instance) return self::$instance;
		return self::$instance = new Container();
	}

	public function get ($name) {
		if ($this->has($name)) return self::$map[$name];
		return null;
	}

	public function set ($name, $value) {
		self::$map[$name] = $value;
	}

	public function has ($name) {
		return isset(self::$map[$name]);
	}

	public function each () {
		var_dump(self::$map);
	}

	public function setMany (array $map) {
		foreach ($map as $key => $value) {
			$this->set($key, $value);
		}
	}

}