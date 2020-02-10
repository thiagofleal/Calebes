<?php

require __DIR__ . '/vendor/autoload.php';

define("LOG_FILE", __DIR__ . "/error.log");

ini_set('max_execution_time', 90);

function print_log($log)
{
	$content = file_get_contents(LOG_FILE);
	$content .= date("d/m/Y - H:i:s") . " - " . $log . "\n";
	file_put_contents(LOG_FILE, $content);
}