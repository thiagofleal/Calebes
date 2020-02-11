<?php

require __DIR__ . '/../bootstrap.php';

use Tonight\Tools\Session;
use Tonight\MVC\Router;
use App\Models\Member;

Session::start();

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