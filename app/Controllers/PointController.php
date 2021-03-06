<?php

namespace App\Controllers;

use stdClass;
use Tonight\MVC\Router;
use Tonight\Tools\Session;
use App\Models\Point;

class PointController extends BaseController
{
	public function index()
	{
		$this->checkLeader();
		$this->setVariable('title', 'Gerenciar pontos');
		$this->setVariable('points', Point::getAll());
		$user = Session::get('user');
		$current_point = $user->getPoint();

		if ($current_point === false) {
			$current_point = NULL;
		} else {
			$current_point = $current_point->getId();
		}

		$this->setVariable('current_point', $current_point);
		$this->render('list-points', 'main-template');
	}

	public function view($args)
	{
		$point = Point::get($args->id);

		if ($point === false) {
			$this->setVariable('title', 'Ponto não encontrado');
			$this->render('point-not-found', 'main-template');
			exit;
		}

		$this->setVariable('title', $point->getName());
		$this->setVariable('point', $point);
		$this->render('point-view', 'main-template');
	}

	public function register()
	{
		$this->checkLeader();
		$this->setVariable('title', 'Cadastrar ponto');
		$this->setVariable('action', Router::getLink('pontos/acao/cadastrar'));
		if (Session::isset('register-point')) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', Session::getFlash('register-point'));
		} else {
			$this->setVariable('flash', false);
			$this->setVariable('alert', [
				'type' => '',
				'text' => ''
			]);
		}
		if (Session::isset('register-point-values')) {
			$this->setVariable('form', Session::getFlash('register-point-values'));
		} else {
			$this->setVariable('form', new stdClass);
		}
		$this->render('form-point', 'main-template');
	}

	public function registerAction($request)
	{
		$this->checkLeader();
		$point = new Point();

		if (empty($request->name)) {
			Session::set('register-point', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Nome"'
			]);
			Session::set('register-point-values', $request);
			Router::redirect('pontos/cadastrar');
			exit;
		}
		if (empty($request->address)) {
			Session::set('register-point', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Endereço"'
			]);
			Session::set('register-point-values', $request);
			Router::redirect('pontos/cadastrar');
			exit;
		}

		$point->setName($request->name);
		$point->setAddress($request->address);
		$point->insert();
		Session::set('register-point', [
			'type' => 'alert-success',
			'text' => 'Ponto cadastrado com sucesso'
		]);
		Session::set('register-point-values', new stdClass);
		Router::redirect('pontos/cadastrar');
	}

	public function edit($args)
	{
		$this->checkLeader();
		$this->setVariable('title', 'Editar ponto');
		$this->setVariable('action', Router::getLink('pontos', $args->id, 'acao/editar'));
		if (Session::isset('edit-point')) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', Session::getFlash('edit-point'));
		} else {
			$this->setVariable('flash', false);
			$this->setVariable('alert', [
				'type' => '',
				'text' => ''
			]);
		}
		$form = new stdClass;
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
		
		if (empty($request->name)) {
			Session::set('edit-point', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Nome"'
			]);
			Session::set('edit-point-values', $request);
			Router::redirect('pontos', $args->id, 'editar');
			exit;
		}
		if (empty($request->address)) {
			Session::set('edit-point', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Endereço"'
			]);
			Session::set('edit-point-values', $request);
			Router::redirect('pontos', $args->id, 'editar');
			exit;
		}

		$point = Point::get($args->id);

		if ($point === false) {
			Session::set('edit-point', [
				'type' => 'alert-danger',
				'text' => 'Ponto não encontrado'
			]);
			Session::set('edit-point-values', $request);
			Router::redirect('pontos', $args->id, 'editar');
			exit;
		}

		$point->setName($request->name);
		$point->setAddress($request->address);
		if ($point->update()){
			Session::set('edit-point', [
				'type' => 'alert-success',
				'text' => 'Ponto atualizado com sucesso'
			]);
			Session::set('edit-point-values', new stdClass);
		} else {
			Session::set('edit-point', [
				'type' => 'alert-danger',
				'text' => 'Erro ao editar ponto'
			]);
		}
		Router::redirect('pontos', $args->id, 'editar');
	}

	public function delete($args)
	{
		$this->checkLeader();
		$point = Point::get($args->id);
		$user = Session::get('user');
		if ($point !== false) {
			if ($user->getPoint() == $args->id) {
				$user->setPoint('');
				$user->update();
			}
			$point->delete();
		}
		Router::redirect('pontos');
	}

	public function addUser($args)
	{
		$this->checkLeader();
		$point = Point::get($args->id);

		if ($point !== false) {
			$user = Session::get('user');
			$user->setPoint($point);
			$user->update();
		}
		Router::redirect('pontos');
	}

	public function researches($args)
	{
		$point = Point::get($args->id);

		$this->checkPoint($point);

		$this->setVariable('title', "Pesquisas");

		if ($point === false) {
			$this->setVariable('researches', array());
		} else {
			$this->setVariable('researches', $point->getVisibleResearches());
		}
		$this->render('list-point-researches', 'main-template');
	}
}