<?php
namespace PCronManager;

class Process {
	
	private $command;
	private $process;

	private $statusInfo;

	private $pipes;

	private $output;

	private $ptIn;
	private $ptOut;
	private $ptErr;


	public function __construct ($command) {

		if (is_array($command)) {
			$cmd = array_unshift($command);
			$args = implode(' ', $command);
			$args = escapeshellarg($args);
			$command = $cmd . ' ' . $args;
		}

		$this->command = $command;
	}


	public function syncRun () {
		if ($this->command == '') return false;

		// proc_open 使用 sh -c 运行脚本，会新开一个进程用于运行指定脚本
		// 添加exec可以迫使其不新开进程，而在原进程中运行，并保持pid一致
		// see https://php.net/manual/en/function.proc-get-status.php#93382
		$this->process = proc_open($this->command, $this->getDescriptor(), $this->pipes);

		if (!is_resource($this->process)) {
			throw new ProcOpenFailedException('');
		}

		$this->ptIn  = $this->pipes[0];
		$this->ptOut = $this->pipes[1];
		$this->ptErr = $this->pipes[2];

		$this->updateStatusInfo();
	}

	public function blockRun () {
		$this->syncRun();
		stream_set_blocking($this->ptOut, 1);
		return $this->getOutput();
	}

	private function updateStatusInfo () {
		return $this->statusInfo = proc_get_status($this->process);
	}

	public function getProcess () {
		return $this->process;
	}

	private function getDescriptor () {
		return [
			array('pipe', 'r'),
        	array('pipe', 'w'), // stdout
        	array('pipe', 'w'), // stderr
		];
	}

	public function getStatusInfo () {
		$this->updateStatusInfo();
		return $this->statusInfo;
	}

	public function getOutput () {
		return stream_get_contents($this->ptOut);
	}

	/**
	 * 获取输出指针
	 */
	public function getOutputPointer () {
		return $this->ptOut;
	}
	/**
	 * 获取输入指针
	 */
	public function getInputPointer () {
		return $this->ptIn;
	}
	/**
	 * 获取error流指针
	 */
	public function getErrPointer () {
		return $this->ptErr;
	}

	public function getPid () {
		return $this->updateStatusInfo()['pid'] ?: null;
	}

	public function isRunning () {
		$this->updateStatusInfo();
		return $this->statusInfo['running'];
	}

	public function stop () {
		proc_terminate($this->process, 9);
	}


}