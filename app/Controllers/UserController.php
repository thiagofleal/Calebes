<?php

namespace App\Controllers;

use Tonight\Server\Session;
use Tonight\MVC\Controller;
use Tonight\MVC\Router;

class UserController extends Controller
{
	public function register()
	{
		$this->setVariable('title', 'Registrar Calebe');
		$this->setVariable('action', Router::getLink('calebe', 'acao', 'cadastrar'));
		if (Session::issetFlash('register-user')) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', Session::getFlash('register-user'));
		} else {
			$this->setVariable('flash', false);
			$this->setVariable('alert', array(
				'type' => '',
				'text' => ''
			));
		}
		$this->render('register-user', 'main-template');
	}

	public function registerAction($request)
	{
		Router::redirect('calebe', 'cadastrar');
	}
}