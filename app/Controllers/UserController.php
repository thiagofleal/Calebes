<?php

namespace App\Controllers;

use Tonight\Tools\Session;
use Tonight\Tools\Request;
use Tonight\MVC\Controller;
use Tonight\MVC\Router;
use App\Models\Member;
use App\Models\Leader;

class UserController extends Controller
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

	private function checkLeaderOrSelf($id)
	{
		$user = Session::get('user');

		if ($user === false) {
			Router::redirect();
			exit;
		}
		if (!$user->isLeader() && $user->getId() != $id) {
			Router::redirect();
			exit;
		}
	}

	public function index()
	{
		$this->checkLeader();
		$user = Session::get('user');
		$this->setVariable('title', 'Gerenciar calebes');
		$this->setVariable('members', Member::getFromPointOrNull($user->getPoint()));
		$this->render('list-users', 'main-template');
	}

	public function register()
	{
		$this->checkLeader();
		$this->setVariable('title', 'Cadastrar Calebe');
		$this->setVariable('action', Router::getLink('calebe/acao/cadastrar'));
		$this->setVariable('require_pass', true);
		if (Session::issetFlash('register-user-values')) {
			$this->setVariable('form', Session::getFlash('register-user-values'));
		} else {
			$this->setVariable('form', new \stdClass);
		}
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
		$this->render('form-user', 'main-template');
	}

	public function registerAction($request)
	{
		$this->checkLeader();
		$member = new Member();

		Session::setFlash('register-user-values', $request);

		if (empty($request->name)) {
			Session::setFlash('register-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Nome"'
			]);
		}
		elseif (empty($request->document)) {
			Session::setFlash('register-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Documento"'
			]);
		}
		elseif (empty($request->document_type)) {
			Session::setFlash('register-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Tipo de documento"'
			]);
		}
		elseif (empty($request->password)) {
			Session::setFlash('register-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Senha"'
			]);
		}
		elseif (empty($request->confirm)) {
			Session::setFlash('register-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Confirmar senha"'
			]);
		}
		elseif ($request->confirm != $request->password) {
			Session::setFlash('register-user', [
				'type' => 'alert-warning',
				'text' => 'Os campos "Senha" e "Confirmar senha" devem possuir o mesmo valor'
			]);
		}
		else {
			$member->setDocument($request->document);
			$member->setDocument_type($request->document_type);
			$member->setName($request->name);
			$member->setBirth($request->get('birth', ''));
			$member->setAddress($request->get('address', ''));
			$member->setPhone($request->get('phone', ''));
			$member->setEmail($request->get('email', ''));
			$member->setTshirt_size($request->get('tshirt_size', ''));
			$member->setPassword($request->password);
			//$member->setPoint(1);

			if ($member->insert()) {
				Session::setFlash('register-user', [
					'type' => 'alert-success',
					'text' => 'Calebe cadastrado com sucesso'
				]);
			} else {
				Session::setFlash('register-user', [
					'type' => 'alert-warning',
					'text' => 'O documento informado já se encontra na base de dados'
				]);
			}
		}

		Router::redirect('calebe/cadastrar');
	}

	public function edit($args)
	{
		$this->checkLeaderOrSelf($args->id);
		$this->setVariable('title', 'Editar Calebe');
		$this->setVariable('action', Router::getLink('calebe', $args->id, 'acao/editar'));
		$this->setVariable('require_pass', false);
		
		if (Session::issetFlash('register-user-values')) {
			$this->setVariable('form', Session::getFlash('register-user-values'));
		} else {
			$member = new Member();
			$form = new \stdClass;

			$member->load($args->id);
			$form->name = $member->getName();
			$form->birth = $member->getBirth();
			$form->address = $member->getAddress();
			$form->phone = $member->getPhone();
			$form->email = $member->getEmail();
			$form->tshirt_size = $member->getTshirt_size();
			$form->document = $member->getDocument();
			$form->document_type = $member->getDocument_type();
		}

		$this->setVariable('form', $form);
		if (Session::issetFlash('edit-user')) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', Session::getFlash('edit-user'));
		} else {
			$this->setVariable('flash', false);
			$this->setVariable('alert', array(
				'type' => '',
				'text' => ''
			));
		}
		$this->render('form-user', 'main-template');
	}

	public function editAction($args, $request)
	{
		$this->checkLeaderOrSelf($args->id);
		$member = new Member();

		Session::setFlash('edit-user-values', $request);

		if (empty($request->name)) {
			Session::setFlash('edit-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Nome"'
			]);
		}
		elseif (empty($request->document)) {
			Session::setFlash('edit-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Documento"'
			]);
		}
		elseif (empty($request->document_type)) {
			Session::setFlash('edit-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Tipo de documento"'
			]);
		}
		elseif ($request->confirm != $request->password) {
			Session::setFlash('edit-user', [
				'type' => 'alert-warning',
				'text' => 'Os campos "Senha" e "Confirmar senha" devem possuir o mesmo valor'
			]);
		}
		else {
			$member->load($args->id);
			$member->setDocument($request->document);
			$member->setDocument_type($request->document_type);
			$member->setName($request->name);
			$member->setBirth($request->get('birth', ''));
			$member->setAddress($request->get('address', ''));
			$member->setPhone($request->get('phone', ''));
			$member->setEmail($request->get('email', ''));
			$member->setTshirt_size($request->get('tshirt_size', ''));
			$member->setPassword($request->get('password', ''));

			if ($member->update()) {
				Session::setFlash('edit-user', [
					'type' => 'alert-success',
					'text' => 'Calebe atualizado com sucesso'
				]);
			} else {
				Session::setFlash('edit-user', [
					'type' => 'alert-warning',
					'text' => 'Erro ao atualizar cadastro'
				]);
			}
		}

		Router::redirect('calebe', $args->id, 'editar');
	}

	public function delete($args)
	{
		$this->checkLeader();
		$member = Member::get($args->id);
		if ($member !== false) {
			$member->delete();
		}
		Router::redirect('calebes');
	}

	public function addLeader($args)
	{
		$this->checkLeader();
		if (Member::get($args->id)) {
			$leader = new Leader();
			$leader->setId($args->id);
			$leader->insert();
		}
		Router::redirect('calebes');
	}

	public function removeLeader($args)
	{
		$this->checkLeader();
		$leader = Leader::get($args->id);
		if ($leader !== false) {
			$leader->delete();
		}
		Router::redirect('calebes');
	}

	public function addPoint($args)
	{
		$this->checkLeader();
		$member = Member::get($args->id);
		$user = Session::get('user');
		if ($member !== false) {
			$member->setPoint($user->getPoint());
			$member->update();
		}
		Router::redirect('calebes');
	}

	public function removePoint($args)
	{
		$this->checkLeader();
		$member = Member::get($args->id);
		if ($member !== false) {
			$member->setPoint('');
			$member->update();
		}
		Router::redirect('calebes');
	}
}