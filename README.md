# pCronManager
A crontab web-based manager written in PHP

### 功能
- 多机器部署
- 可以限制单台机器某脚本的运行上限
- 命令路径和输出路径中可以使用变量和简单的php函数
- 日志中记录了单个任务的起止时间，运行时长，并行数量，pid

### 环境要求
php5.4+
\*nix
php拓展：pdo,pdo_mysql,pcntl

## 使用
### composer install

```sh
composer install
```

### 配置
- general config
```php
// 服务器编号
'serverId' => 1,
// 程序中会使用php再启动一个额外脚本，如果你的php不在/usr/bin/php下，请指定绝对路径
'php' => '/usr/bin/php',

```
- db config
```php

'db' => [
		'db_type' => 'mysql', // 数据库类型，目前仅支持mysql

		'mysql' => [
			'host'     => '127.0.0.1',
			'port'     => 3306,
			'user'     => 'root',
			'password' => '123456',
			'database' => 'scheduler'
		]
	]

```

### 执行install.php

```
php install.php
```
install.php 会检查必须的php拓展是否安装，并初始化数据库（需要事先配置好数据库）

### 添加一条crontab规则
```sh
* * * * *   /usr/bin/php /path/scheduler/index.php
```
### 添加任务
- 添加标签，用于对任务进行分组。
- 添加变量，变量可以是普通的字符串，也可以是php原生函数，用作函数的时候，需要添加前缀fn:，例如：fn:date('Ymd')。
- 添加任务。
- 查看日志，观察下运行时间，运行时长是否正确，是否有输出。

### 新增机器
所有机器需要访问同一个数据库。添加新机器，配置文件中使用不和其他机器重复的serverId，并添加crontab规则即可。然后便可以在后台添加此机器的任务

## 管理后台

管理后台使用Laravel-Admin， 需要php7.0+

在/admin目录下 执行 composer install

默认账号密码 admin / admin

