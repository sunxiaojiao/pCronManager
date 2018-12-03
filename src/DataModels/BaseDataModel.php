<?php
namespace PCronManager\DataModels;
use PCronManager\Db;

class BaseDataModel {
	protected $db;
	protected $tableName;

	public function __construct () {
		$this->db = Db::instance();
	}	

	// public function getAll () {
	// 	return $db->getData("select * from {$this->tableName} limit 20");
	// }

	public function getOne ($id) {
		return $this->db->getRow("select * from {$this->tableName} where `id` = {$id}");
	}

}