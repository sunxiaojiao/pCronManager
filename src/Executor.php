<?php
namespace PCronManager;

if (function_exists('pcntl_async_signals')) {
	pcntl_async_signals(true);
}

class Executor {
	private $commandWithArgs;
	private $maxConcurrenceCount;

	private $uniqId;

	private $container;

	private $startTime;

	private $job;

	public function __construct ($commandId, $uniqId) {
		$this->startTime = time();
		$this->container = Container::instance();

		$jobsModel = new DataModels\JobsModel();
		$this->job = $jobsModel->getOne($commandId);
		$server = new Server();
		$this->job['command'] = $server->matchVars($this->job['command']);
		$this->job['output'] = $server->matchVars($this->job['output']);
		Logger::log($this->job['output']);

		$this->commandWithArgs = $this->job['command'];
		$this->maxConcurrenceCount = $this->job['max_concurrence'];
		$this->uniqId = $uniqId;

		register_shutdown_function([$this, 'shutdownHandler']);

		if (function_exists('pcntl_signal')) {
			pcntl_signal(SIGINT, [$this, 'sigHandler']);
			pcntl_signal(SIGTERM, [$this, 'sigHandler']);
		}
		
	}

	public function exec () {
		$shouldSignalDispath = !function_exists('pcntl_async_signals') && function_exists('pcntl_signal_dispatch');
		if ($shouldSignalDispath) pcntl_signal_dispatch();

		$process = new Process($this->commandWithArgs);
		
		$process->syncRun();

		$pid = $process->getPid();

		Logger::log($this->uniqId, $pid);

		// 保证之前的log已经插入
		usleep(1000);
		Logger::updateJobLog($this->uniqId, ['pid' => $pid]);

		$pt = $process->getOutputPointer();
		Logger::log($this->job['output']);

		// 如果目标脚本执行的足够快，到这里的时候脚本已经结束了
		// 但是输出指针中还可能存在输出
		while($process->isRunning() && $line = fgets($pt)) {
			Logger::log($line);

			if ($shouldSignalDispath) pcntl_signal_dispatch();

			$this->output($this->job['output'], $line);
			usleep(1000);
			unset($line);
		}

		$remainingOutput = stream_get_contents($pt);
		$this->output($this->job['output'], $remainingOutput);
		fclose($pt);
	}

	public function shutdownHandler () {

		Logger::updateJobLog($this->uniqId, [
			'end_time' => date('Y-m-d H:i:s'),
			'running_time' =>  time() - $this->startTime
		]);
		Logger::log('exit');
	}

	private function sigHandler ($signo) {
		exit();
	}

	public function __destruct () {

	}

	private function output ($path, $log) {
		if ($path == '') return;
		
		if (!file_exists($path)) {
			$parts = explode('/', $path);
			// var_dump($parts);
			if ($parts[0] === '') unset($parts[0]);
	        $file = array_pop($parts);
	        $dir = '';
	        foreach($parts as $part) {
	        	if(!is_dir($dir .= "/$part")) mkdir($dir);
	        }
		}
		
        file_put_contents($path, $log, FILE_APPEND);
	}

}



