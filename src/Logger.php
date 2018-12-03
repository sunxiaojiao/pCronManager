<?php
namespace PCronManager;

class Logger {

	public static function log () {
		$message = implode(' | ', array_merge([date(DATE_ATOM)], func_get_args()));
		return error_log($message . PHP_EOL, 3, INSTALL_PATH . '/log.log');	
	}

	public static function logJob ($job) {

		$params = [
			'command_id'   => $job->commandId,
			'server_id'    => $job->server->getId(),
			'command'      => $job->commandWithArgs,
			'create_time'  => date('Y-m-d H:i:s'),
			// 'running_time' => ,
			'start_time'   => date('Y-m-d H:i:s', $job->startAt),
			// 'end_time'     => '',
			'uniq_id'      => $job->uniqId,
			// 'pid'          => '',
			'concurrent'   => $job->runningJobCount() + 1,
		];
		$logsModel = new DataModels\LogsModel();
		$logsModel->add($params);
	}

	public static function updateJobLog ($uniqId, $params) {
		$logsModel = new DataModels\LogsModel();
		return $logsModel->update($uniqId, $params);
	}
}