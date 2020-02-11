<?php

Tonight\MVC\Config::setUrlGetter( function() {
	$folder = 'calebes/';
	$uri = $_SERVER['REQUEST_URI'];
	return substr($uri, strlen($folder));
});