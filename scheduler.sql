
CREATE TABLE `jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '命令名称用途',
  `command` varchar(1000) NOT NULL DEFAULT '' COMMENT '执行的命令',
  `command_md5` varchar(32) NOT NULL DEFAULT '' COMMENT '命令md5',
  `server_id` int(11) NOT NULL DEFAULT '0' COMMENT '执行的机器编号',
  `cron` varchar(200) NOT NULL DEFAULT '' COMMENT 'cron 表达式',
  `output` varchar(1000) NOT NULL DEFAULT '' COMMENT '输出定向到某个文件',
  `max_concurrence` int(11) NOT NULL DEFAULT '1' COMMENT '同时运行的最大数量',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态 0:关闭 1:开启',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `last_run_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上次运行时间',
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uniq_commandMd5_serverId` (`command_md5`,`server_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;



CREATE TABLE `logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `command_id` int(10) unsigned NOT NULL DEFAULT '0',
  `server_id` int(10) unsigned NOT NULL DEFAULT '0',
  `command` varchar(200) NOT NULL DEFAULT '',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `running_time` int(11) NOT NULL DEFAULT '0' COMMENT '运行时长 秒',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '启动时间',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
  `uniq_id` varchar(20) NOT NULL DEFAULT '',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '当前进程pid',
  `concurrent` int(11) NOT NULL DEFAULT '0' COMMENT '当前脚本的运行数量',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1658 DEFAULT CHARSET=utf8mb4;



CREATE TABLE `tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;



CREATE TABLE `vars` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '变量名',
  `value` varchar(200) NOT NULL DEFAULT '' COMMENT '值',
  `server_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属机器编号',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uniq_name_serverId` (`name`,`server_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

