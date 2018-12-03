<?php
namespace PCronManager\DataModels;

class LogsModel extends BaseDataModel {
	protected $tableName = 'logs';

	public function add ($params) {
		return $this->db->insert($this->tableName, $params);
	}

	public function update($uniqId, $params) {
		return $this->db->update($this->tableName, ['uniq_id' => $uniqId], $params);
	}
}