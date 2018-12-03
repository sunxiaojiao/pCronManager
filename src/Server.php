<?php
namespace PCronManager;

class Server {

	private $id;

	private $ip;

	private $vars;

	public function __construct ($serverId = 0, $ip = '') {
		$this->id = Config::get('serverId');

		$this->ip = $ip;

		$this->vars = $this->getVars();

	}

	private function getVars () {
		$model = new DataModels\VarsModel();
		$vars = $model->getAll($this->id);

		foreach ($vars as $key => $value) {
			$this->vars[$value['name']] = $value['value'];
		}
		
		return $this->vars;
	}

	public function matchVars ($command) {
		preg_match_all("/{(.+?)}/", $command, $matches);

		foreach ($matches[1] as $key => $value) {
			$replace = $this->vars[$value];
			if(substr($this->vars[$value], 0, 3) === 'fn:') {
				$fnString = 'return ' . substr($this->vars[$value], 3) . ';';
				$replace = eval($fnString);
			}
			$command = str_replace('{' . $value . '}', $replace ?: '', $command);
		}

		return $command;
	}

	public function getId () {
		return $this->id;
	}
}