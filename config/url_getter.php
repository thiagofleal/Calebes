<?php

Tonight\MVC\Config::setUrlGetter( function() {
	$folder = 'calebes/';
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	return substr($uri, strlen($folder));
});