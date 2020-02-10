<?php

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../config/environment.php';
require __DIR__ . '/../config/routes.php';
require __DIR__ . '/../config/dbconfig.php';

use Tonight\Tools\Request;
use Tonight\Tools\Session;
use Tonight\MVC\Config;
use Tonight\MVC\Router;
use App\Models\Member;

Session::start();

Config::setUrlGetter( function() {
	$request = Request::getMode();
	return $request->get('url', '');
});

if (Session::isset('user') && Session::get('user') !== false) {
	$user = Session::get('user');
	Session::set('user', Member::get($user->getId()));
} else {
	Session::set('user', false);
}

try
{
	(new Router())->run();
}
catch(Exception $e)
{
	print_log($e->getMessage());
	Router::redirect('erros', 'Erro interno', 'Exceção não tratada');
}