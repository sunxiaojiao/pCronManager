<?php
namespace PCronManager;

class Ps {

	private $shell = 'ps axo pid,stat,command | grep ';

	public function getComandCount ($command) {
		$startTime = microtime(true);
		$lists = $this->getCommandList($command);
		// var_dump($lists);
		$count = 0;
		foreach ($lists as $l) {
			if (substr($l['stat'], 0, 1) == 'Z') {
				$this->kill($l['pid']);
			} else {
				$count++;
			}
		}

		return $count;
		
	}

	public function getCommandList ($command) {
		$command = trim($command);
		$process = new Process($this->shell . "'{$command}'");
		$process->syncRun();
		$pt = $process->getOutputPointer();

		$list = [];
		while($line = fgets($pt)) {
			$line = trim($line);

			$temp = explode(' ', $line);

			$pid = 0;
			$stat ='';
			$cmd = '';
			foreach ($temp as $key => $value) {
				if ($value == '') continue;

				if (!$pid) {
					$pid = $value;
					continue;
				}

				if (!$stat) {
					$stat = $value;
					continue;
				}

				$cmd .= $value . ' ';
			}

			$cmd = trim($cmd);
			
			if ($cmd != $command) continue;

			$list[] = [
				'pid'  => $pid,
				'stat' => $stat,
				'command' => $cmd,
			];
		}

		return $list;

	}

	private function kill ($pid, $sig = 9) {
		return posix_kill($pod, $sig);
	}

}
