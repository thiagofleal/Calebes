<?php

namespace App\Controllers;

use Tonight\Tools\Session;
use Tonight\MVC\Router;
use App\Models\Member;

class LoginController extends BaseController
{
	public function index()
	{
		$this->setVariable('title', "Login");
		$this->setVariable('action', Router::getLink('login/acao'));
		if (Session::issetFlash('login')) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', Session::getFlash('login'));
		} else {
			$this->setVariable('flash', false);
			$this->setVariable('alert', [
				'type' => '',
				'text' => ''
			]);
		}
		$this->render('login', 'main-template');
	}

	public function action($request)
	{
		if (empty($request->user)) {
			Session::setFlash('login', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Usuário"'
			]);
			Router::redirect('login');
			exit;
		}
		if (empty($request->password)) {
			Session::setFlash('login', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Senha"'
			]);
			Router::redirect('login');
			exit;
		}

		$user = Member::login($request->user, $request->password);

		if ($user === false) {
			Session::setFlash('login', [
				'type' => 'alert-danger',
				'text' => 'Usuário e/ou senha incorretos'
			]);
			Router::redirect('login');
		} else {
			Session::set('user', $user);
			Router::redirect();
		}
	}

	public function logout()
	{
		Session::set('user', false);
		Router::redirect();
	}
}