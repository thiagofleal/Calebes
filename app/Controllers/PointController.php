<?php

namespace App\Controllers;

use Tonight\MVC\Controller;
use Tonight\MVC\Router;
use Tonight\Server\Session;
use App\Models\Point;

class PointController extends Controller
{
	private function checkLeader()
	{
		$user = Session::get('user');

		if ($user === false) {
			Router::redirect();
			exit;
		}
		if (!$user->isLeader()) {
			Router::redirect();
			exit;
		}
	}

	public function index()
	{
		$this->checkLeader();
		$this->setVariable('title', 'Gerenciar pontos');
		$this->setVariable('points', Point::getAll());
		$user = Session::get('user');
		$this->setVariable('current_point', $user->getPoint());
		$this->render('list-points', 'main-template');
	}

	public function register()
	{
		$this->checkLeader();
		$this->setVariable('title', 'Cadastrar ponto');
		$this->setVariable('action', Router::getLink('ponto/acao/cadastrar'));
		if (Session::issetFlash('register-point')) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', Session::getFlash('register-point'));
		} else {
			$this->setVariable('flash', false);
			$this->setVariable('alert', [
				'type' => '',
				'text' => ''
			]);
		}
		if (Session::issetFlash('register-point-values')) {
			$this->setVariable('form', Session::getFlash('register-point-values'));
		} else {
			$this->setVariable('form', new \stdClass);
		}
		$this->render('form-point', 'main-template');
	}

	public function registerAction($request)
	{
		$this->checkLeader();
		$point = new Point();

		if (empty($request->name)) {
			Session::setFlash('register-point', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Nome"'
			]);
			Session::setFlash('register-point-values', $request);
			Router::redirect('ponto', 'cadastrar');
			exit;
		}
		if (empty($request->address)) {
			Session::setFlash('register-point', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Endereço"'
			]);
			Session::setFlash('register-point-values', $request);
			Router::redirect('ponto', 'cadastrar');
			exit;
		}

		$point->setName($request->name);
		$point->setAddress($request->address);
		$point->insert();
		Session::setFlash('register-point', [
			'type' => 'alert-success',
			'text' => 'Ponto cadastrado com sucesso'
		]);
		Session::setFlash('register-point-values', new \stdClass);
		Router::redirect('ponto', 'cadastrar');
	}

	public function edit($args)
	{
		$this->checkLeader();
		$this->setVariable('title', 'Editar ponto');
		$this->setVariable('action', Router::getLink('ponto', $args->id, 'acao/editar'));
		if (Session::issetFlash('edit-point')) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', Session::getFlash('edit-point'));
		} else {
			$this->setVariable('flash', false);
			$this->setVariable('alert', [
				'type' => '',
				'text' => ''
			]);
		}
		$form = new \stdClass;
		$point = new Point();
		$point->load($args->id);
		$form->name = $point->getName();
		$form->address = $point->getAddress();
		$this->setVariable('form', $form);
		$this->render('form-point', 'main-template');
	}

	public function editAction($args, $request)
	{
		$this->checkLeader();
		$point = new Point();

		if (empty($request->name)) {
			Session::setFlash('edit-point', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Nome"'
			]);
			Session::setFlash('edit-point-values', $request);
			Router::redirect('ponto', $args->id, 'editar');
			exit;
		}
		if (empty($request->address)) {
			Session::setFlash('edit-point', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Endereço"'
			]);
			Session::setFlash('edit-point-values', $request);
			Router::redirect('ponto', $args->id, 'editar');
			exit;
		}

		$point->load($args->id);
		$point->setName($request->name);
		$point->setAddress($request->address);
		if ($point->update()){
			Session::setFlash('edit-point', [
				'type' => 'alert-success',
				'text' => 'Ponto atualizado com sucesso'
			]);
			Session::setFlash('edit-point-values', new \stdClass);
		} else {
			Session::setFlash('edit-point', [
				'type' => 'alert-danger',
				'text' => 'Erro ao editar ponto'
			]);
		}
		Router::redirect('ponto', $args->id, 'editar');
	}

	public function delete($args)
	{
		$this->checkLeader();
		$point = Point::get($args->id);
		if ($point !== false) {
			$point->delete();
		}
		Router::redirect('pontos');
	}

	public function addUser($args)
	{
		$this->checkLeader();
		$user = Session::get('user');
		$user->setPoint($args->id);
		$user->update();
		Router::redirect('pontos');
	}
}