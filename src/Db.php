<?php
namespace PCronManager;
error_reporting(E_ALL & ~E_NOTICE &~E_WARNING);
use \PDO;

class Db {

	private static $instance;

	private $db;

	private function __construct () {
		$this->connect();
	}

	private function connect () {
		$dbConfigs = Config::get('db');

		$dbConfig = $dbConfigs[$dbConfigs['db_type']];

		if (empty($dbConfig)) throw new \Exception('no available db config');

		$dbConfig['db_type'] = $dbConfigs['db_type'];

		$options = [	
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		];

		return $this->db = $this->createPdoConnection($dbConfig, $options);
	}

	public static function instance () {
		if (self::$instance) return self::$instance;
		return self::$instance =  new Db();
	}

	private function prepareAndSendSql ($sql, array $params) {
		Logger::log($sql);

		$count = 3;
		do {
			$count--;
			try {
				$stmt = $this->executeSql($sql, $params);
				$count = 0;
			} catch (\PDOException $e) {
				Logger::log($e->getMessage());
				if ($this->isLostConnection($e)) {
					$this->connect();
				} else {
					throw $e;
				}
			}

		} while ($count > 0);
		
		return $stmt;
	}

	public  function getData($sql, $params = []) {
		$stmt = $this->prepareAndSendSql($sql, $params);
		return $stmt->fetchAll();
	}

	public function getRow ($sql, $params = []) {
		$sql .= ' limit 1';
		$stmt = $this->prepareAndSendSql($sql, $params);
		return $stmt->fetch();
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
		
		$stmt = $this->prepareAndSendSql($sql, $params);
		
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
		
		$stmt = $this->prepareAndSendSql($sql, []);	
		
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
		$stmt = $this->prepareAndSendSql($sql, $params);
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

	private function createPdoConnection ($dbConfig, $options) {
		if ($dbConfig['db_type'] !== 'mysql') throw new \Exception("目前仅支持mysql");
		
		return new PDO(
			"mysql:dbname={$dbConfig['database']};host={$dbConfig['host']};port={$dbConfig['port']}", 
			$dbConfig['user'], 
			$dbConfig['password'], 
			$options
		);
	}

	private function prepare ($sql, array $params) {
		$stmt = $this->db->prepare($sql);

		$i = 1;
		foreach ($params as &$value) {
			$type = PDO::PARAM_STR;
			if (is_numeric($value)) $type = PDO::PARAM_INT;
			$stmt->bindParam($i++, $value, $type);
		}

		return $stmt;
	}

	/**
	 * 是否连接已经断开
	 * @param  [Exception]  $exception 
	 * @return boolean
	 */
	private function isLostConnection ($exception) {
		$message = $exception->getMessage();
		return strpos($message, 'server has gone away') !== false;
	}

	private function executeSql ($sql, array $params) {
		$stmt = $this->prepare($sql, $params);
		$stmt->execute();
		return $stmt;
	}

}
