<?php
namespace PCronManager;

define('INSTALL_PATH', dirname(__FILE__) . '/..');
define('SRC_PATH', INSTALL_PATH .  '/src');
require SRC_PATH . '/boot.php';

$options = getopt('', ['commandId::', 'uniqId::']);

if (!isset($options['commandId'])) exit('缺少 commandId');
if (!isset($options['uniqId'])) exit('缺少 uniqId');

extract($options);
$executor = new Executor($commandId, $uniqId);
$executor->exec();
