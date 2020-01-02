<?php

namespace App\Controllers;

use Tonight\MVC\Controller;
use Tonight\MVC\Router;
use Tonight\Server\Session;
use App\Models\Point;

class PointController extends Controller
{
	public function index()
	{
		;
	}

	public function register()
	{
		$this->setVariable('title', 'Cadastrar ponto');
		$this->setVariable('action', Router::getLink('ponto','acao', 'cadastrar'));
		if (Session::issetFlash('register-point')) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', Session::getFlash('register-point'));
		} else {
			$this->setVariable('flash', false);
			$this->setVariable('alert', array(
				'type' => '',
				'text' => ''
			));
		}
		$this->render('register-point', 'main-template');
	}

	public function registerAction($request)
	{
		$point = new Point();

		$point->setName($request->name);
		$point->setAddress($request->address);
		$point->insert();
		Session::setFlash('register-point', array(
			'type' => 'alert-success',
			'text' => 'Ponto cadastrado com sucesso'
		));
		Router::redirect('ponto', 'cadastrar');
	}
}