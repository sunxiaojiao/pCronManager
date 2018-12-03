<?php
namespace PCronManager\DataModels;

class JobsModel extends BaseDataModel {
	protected $tableName = 'jobs';

	public function getAll ($serverId) {
		$sql = "select * from {$this->tableName} where `server_id` = {$serverId} and `status` = 1";
		return $this->db->getData($sql);
	}

	public function update ($wheres ,$params) {
		return $this->db->update($this->tableName, $wheres, $params);
	}

	public function updateLastRunTime ($id) {
		return $this->update(['id' => $id], [
			'last_run_time' => date('Y-m-d H:i:s')
		]);
	}
}