<?php
namespace PCronManager;
error_reporting(E_ALL & ~E_NOTICE &~E_WARNING);
define('INSTALL_PATH', dirname(__FILE__) . '/..');
define('SRC_PATH', INSTALL_PATH .  '/src');
require SRC_PATH . '/boot.php';

$options = getopt('', ['type::', 'uniqId::', 'startTime::', 'pid::']);

if (!isset($options['type'])) exit('缺少 type');
if (!isset($options['uniqId'])) exit('缺少 uniqId');


switch ($options['type']) {
    case 'pid':
        Logger::updatePid($options['uniqId'], $options['pid']);
        break;
    case 'afterExec':
        $endTime = time();
        Logger::updateJobLog($options['uniqId'], [
            'end_time' => date('Y-m-d H:i:s', $endTime),
            'running_time' =>  $endTime - $options['startTime']
        ]);
}

