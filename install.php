<?php

// 环境监测
echo 'current PHP_VERSION: ' . PHP_VERSION . PHP_EOL;
$required = [
	'pcntl',
	'PDO',
	'pdo_mysql',
];

foreach ($required as $key => $value) {
	if (extension_loaded($value)) {
		echo $value . ' ok' . PHP_EOL;
	} else {
		throw new Exception($value. ' not installed', 1);
	}	
}

define('INSTALL_PATH', dirname(__FILE__));
require INSTALL_PATH . '/src/boot.php';

// 创建数据库结构
$db = PCronManager\Db::instance();
$dbConfig = PCronManager\Config::get('db');
$dbName = $dbConfig['mysql']['database'];
$db->exec("create database if not exists `{$dbName}`");

$createTableSql = "use {$dbName};";
$createTableSql .= file_get_contents(INSTALL_PATH . '/scheduler.sql');

$flag = $db->exec($createTableSql);
echo '数据库创建成功';

