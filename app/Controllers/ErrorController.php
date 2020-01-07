<?php

namespace App\Controllers;

use Tonight\MVC\Router;

class ErrorController extends BaseController
{
	public function notFound()
	{
		$this->setVariable('title', "Página não encontrada");
		$this->setVariable('link', Router::getLink());
		$this->render('404', 'main-template');
	}

	public function show($args)
	{
		$msg = $args->message ?? 'Erro interno';
		$this->setVariable('title', "Erro");
		$this->setVariable('error_type', $args->type);
		$this->setVariable('error_message', $msg);
		$this->render('error', 'main-template');
	}
}