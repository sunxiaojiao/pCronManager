<?php
return [

	// 服务器编号
	'serverId' => 1,

	// php 所在路径
	// 'php' => '/usr/local/opt/php@7.0/bin/php',
	'php' => '/usr/bin/php',

	// db
	'db' => [
		'db_type' => 'mysql',

		'mysql' => [
			'host'     => '127.0.0.1',
			'port'     => 3306,
			'user'     => 'root',
			'password' => 'root',
			'database' => 'scheduler'
		]
	],

	// redis
	// 'redis' => [
	// 	'host' => '127.0.0.1',
	// 	'port' => '6379',
	// 	'db'   => '0',
	// 	'auth' => null,
	// 	'connect_timeout' => 1,
	// 	'read_timeout'    => 1,
	// 	'retry_interval' => 200,
	// ]

];