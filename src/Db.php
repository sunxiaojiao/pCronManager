<?php
namespace PCronManager;
use \PDO;

class Db {

	private static $instance;

	private $db;

	private function __construct () {
		$this->connect();
	}

	private function connect () {
		if ($this->db) return $this->db;

		$dbConfig = Config::get('db');

		if ($dbConfig['db_type'] === 'mysql') {
			$connConfig = $dbConfig['mysql'];
			$options = [];
			$this->db = new PDO("mysql:dbname={$connConfig['database']};host={$connConfig['host']};port={$connConfig['port']}", $connConfig['user'], $connConfig['password'], $options);
			return $this->db;
		} 
	
		throw new \Exception('no available db config');
		
	}

	public static function instance () {
		if (self::$instance) return self::$instance;
		return self::$instance =  new Db();
	}

	private function prepareSql ($sql, array $params) {
		Logger::log($sql);
		$stmt = $this->db->prepare($sql);

		$i = 1;
		foreach ($params as &$value) {
			$type = PDO::PARAM_STR;
			if (is_numeric($value)) $type = PDO::PARAM_INT;
			$stmt->bindParam($i++, $value, $type);
		}

		$flag = $stmt->execute();
		if ($flag === false) {
			$errorInfo = $stmt->errorInfo();
			throw new \Exception($errorInfo[0] . $errorInfo[1] . $errorInfo[2]);
		}

		return $stmt;
	}

	public  function getData($sql, $params = []) {
		$stmt = $this->prepareSql($sql, $params);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getRow ($sql, $params = []) {
		$sql .= ' limit 1';
		$stmt = $this->prepareSql($sql, $params);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function insert ($tableName, $params) {
		$sql = "insert into `{$tableName}`";
		$keys = [];
		$values = [];
		foreach ($params as $key => $value) {
			$keys[] = "`{$key}`";
			$values[] = '?';
		}
		$keys = implode(',', $keys);
		$values = implode(',', $values);

		$sql .= "({$keys}) values ({$values})";

		$stmt = $this->prepareSql($sql, $params);
		return $this->db->lastInsertId();
	}

	public function update ($tableName, $wheres, $params) {
		$sql = "update `{$tableName}` set ";
		$kv = [];
		foreach ($params as $key => $value) {
			if (is_string($value)) $value = "'{$value}'";
			$kv[] = "`{$key}` = {$value}";
		}
		$kv = implode(',', $kv);
		$sql .= $kv;

		$sql .= ' where ' .$this->whereSql($wheres);
		$stmt = $this->prepareSql($sql, []);
		return $stmt->rowCount();
	}

	private function whereSql ($params) {
		$temp = [];
		foreach ($params as $key => $value) {
			if (is_string($value)) $value = "'{$value}'";
			$temp[] = "`{$key}` = {$value}";
		}
		return implode(',', $temp);
	}

	public function query ($sql, $params = []) {
		$stmt = $this->prepareSql($sql, $params);
		return $stmt;
	}

	public function exec ($sql) {
		$ret = $this->db->exec($sql);
		if (false === $ret) {
			$errorInfo = $this->db->errorInfo();
			throw new \Exception($errorInfo[0] . $errorInfo[1] . $errorInfo[2]);
		}
		return $ret;
	}

	public function __call($func, $args) {
		return call_user_func_array($func, $args);
	}

	public function __destruct () {

	}

	private function __clone () {

	}

}
