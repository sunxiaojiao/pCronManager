<?php
namespace PCronManager\DataModels;

class VarsModel extends BaseDataModel {
	protected $tableName = 'vars';

	public function getAll ($serverId) {
		return $this->db->getData("select * from {$this->tableName} where `server_id` = {$serverId} limit 20");
	}
}