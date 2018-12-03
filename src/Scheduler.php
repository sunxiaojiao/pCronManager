<?php
/**
 * 任务调度
 */

namespace PCronManager;

class Scheduler {

	private $container;

	private $server;

	public function __construct () {
		$this->server = new Server();
	}

	public function getJobs () {
		$jobsModel = new DataModels\JobsModel();
		$jobs = $jobsModel->getAll($this->server->getId());
		
		return array_map(function ($job) {

			return new Job($job['id'], $job['command'], $job['cron'] , $job['max_concurrence']);

		}, $jobs);
	}

	public function run () {
		$jobs = $this->getJobs();

		if (empty($jobs)) return;

		foreach ($jobs as $key => $job) {
			// 是否到了可以运行的时间
			if (!$job->isDue()) continue;
			// 是否没有限制运行的条件
			if (!$job->isAllowedRun()) continue;

			$beginTime = microtime(true);

			$process = $job->runInBackground();

			Logger::logJob($job);

			$upTime = round( (microtime(true) - $beginTime) * 1000, 2 );

			$job->updateLastRunTime();
		}
	}


}