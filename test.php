<?php
$i = 0;
$fp = fopen(dirname(__FILE__) . '/1.log', 'a');
while (true) {
	$ret = date(DATE_ATOM)  . ' | ' . $i++ . PHP_EOL;
	echo $ret;
	fwrite($fp, $ret);
	if ($i >= 10) break;
	sleep(1);
}

fclose($fp);
