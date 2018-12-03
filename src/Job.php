<?php
namespace PCronManager;
use Cron\CronExpression;

class Job {

	private $oldCommandWithArgs;
	private $commandWithArgs;
	private $maxConcurrenceCount;
	private $cronExpression = '';

	private $commandId;
	// 每个运行的脚本用一个唯一id标记
	private $uniqId;

	private $startAt;
	private $server;

	private $process;

	private $container;


	public function __construct ($commandId , $commandWithArgs, $cronExpression, $maxConcurrenceCount = 1) {
		$this->container = Container::instance();

		$this->uniqId = intval(microtime(true) * 1000) . mt_rand(1, 1000);	

		$this->server = new Server();

		$this->oldCommandWithArgs = $commandWithArgs;
		$this->commandWithArgs = $this->server->matchVars($commandWithArgs);

		$this->commandId = $commandId;
		$this->cronExpression = $cronExpression;
		$this->maxConcurrenceCount = $maxConcurrenceCount;
	}

	public function isRunning () {
		if (!$this->process) return false;

		return $this->process->isRunning();
	}

	public function isStoped () {
		return !$this->isRunning();
	}

	/**
	 * 阻塞运行
	 */
	public function runInForeground () {
		$this->process = new Process($this->commandWithArgs);

		$this->process->blockRun();

		return $this->process;
	}

	public function stop () {
		if ($this->process) {
			$this->process->stop();
		}
	}

	/**
	 * 正在运行的数量
	 * @return int
	 */
	public function runningJobCount () {
		$ps = new Ps();
		return $ps->getComandCount($this->commandWithArgs);
	}

	/**
	 * 后台异步运行
	 */
	public function runInBackground () {
		$command = Config::get('php') . ' ' . SRC_PATH . "/run.php --commandId={$this->commandId} --uniqId={$this->uniqId}";
		var_dump($command);
		$this->process = new Process($command);

		$this->process->syncRun();

		$this->startAt = time();

		return $this->process;
	}


	public function isAllowedRun () {
		return $this->maxConcurrenceCount > $this->runningJobCount();
	}

	/**
	 * is time to run?
	 */
	public function isDue () {
		return CronExpression::factory($this->cronExpression)->isDue();
	}

	public function __get ($name) {
		if (isset($this->$name)) return $this->$name;
		return null;
	}

	
	public function updateLastRunTime () {
		$model = new DataModels\JobsModel();
		$model->updateLastRunTime($this->commandId);
	}

	public function setUniqId ($id) {
		return $this->uniqId = $id;
	}

	public function getCommandWithArgs () {
		return $this->commandWithArgs;
	}
  
	public function getProcess () {
		return $this->process;
	}


}
