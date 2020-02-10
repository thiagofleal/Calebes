<?php

namespace App\Controllers;

use stdClass;
use Tonight\Tools\Session;
use Tonight\Tools\Request;
use Tonight\MVC\Router;
use App\Models\Member;
use App\Models\Leader;
use App\Models\Point;

class UserController extends BaseController
{
	public function index()
	{
		$this->checkLeader();
		$user = Session::get('user');
		$point = $user->getPoint();
		$this->setVariable('title', 'Gerenciar calebes');

		if ($point === false) {
			$members_point = array();
		} else {
			$members_point = $point->getMembers();
		}

		$members_null = Member::getNotPoint();
		$this->setVariable('members', array_merge($members_point, $members_null));
		$this->render('list-users', 'main-template');
	}

	public function register()
	{
		$this->checkLeader();
		$this->setVariable('title', 'Cadastrar Calebe');
		$this->setVariable('action', Router::getLink('membros/acao/cadastrar'));
		$this->setVariable('require_pass', true);
		if (Session::isset('register-user-values')) {
			$this->setVariable('form', Session::getFlash('register-user-values'));
		} else {
			$this->setVariable('form', new stdClass);
		}
		if (Session::isset('register-user')) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', Session::getFlash('register-user'));
		} else {
			$this->setVariable('flash', false);
			$this->setVariable('alert', [
				'type' => '',
				'text' => ''
			]);
		}
		$this->render('form-user', 'main-template');
	}

	public function registerAction($request)
	{
		$this->checkLeader();
		$user = Session::get('user');
		$member = new Member();

		Session::set('register-user-values', $request);

		if (empty($request->name)) {
			Session::set('register-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Nome"'
			]);
		}
		elseif (empty($request->document)) {
			Session::set('register-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Documento"'
			]);
		}
		elseif (empty($request->document_type)) {
			Session::set('register-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Tipo de documento"'
			]);
		}
		elseif (empty($request->password)) {
			Session::set('register-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Senha"'
			]);
		}
		elseif (empty($request->confirm)) {
			Session::set('register-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Confirmar senha"'
			]);
		}
		elseif ($request->confirm != $request->password) {
			Session::set('register-user', [
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
			$member->setPoint($user->getPoint());

			if ($member->insert()) {
				Session::set('register-user', [
					'type' => 'alert-success',
					'text' => 'Calebe cadastrado com sucesso'
				]);
			} else {
				Session::set('register-user', [
					'type' => 'alert-warning',
					'text' => 'O documento ou email já se encontra na base de dados'
				]);
			}
		}

		Router::redirect('membros/cadastrar');
	}

	public function edit($args)
	{
		$this->checkLeaderOrSelf($args->id);
		$this->setVariable('title', 'Editar Calebe');
		$this->setVariable('action', Router::getLink('membros', $args->id, 'acao/editar'));
		$this->setVariable('require_pass', false);
		
		if (Session::isset('register-user-values')) {
			$this->setVariable('form', Session::getFlash('register-user-values'));
		} else {
			$member = Member::get($args->id);
			if ($member !== false) {
				$form = new stdClass;
				$form->name = $member->getName();
				$form->birth = $member->getBirth();
				$form->address = $member->getAddress();
				$form->phone = $member->getPhone();
				$form->email = $member->getEmail();
				$form->tshirt_size = $member->getTshirt_size();
				$form->document = $member->getDocument();
				$form->document_type = $member->getDocument_type();
			} else {
				$form = new stdClass;
			}
		}

		$this->setVariable('form', $form);
		if (Session::isset('edit-user')) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', Session::getFlash('edit-user'));
		} else {
			$this->setVariable('flash', false);
			$this->setVariable('alert', [
				'type' => '',
				'text' => ''
			]);
		}
		$this->render('form-user', 'main-template');
	}

	public function editAction($args, $request)
	{
		$this->checkLeaderOrSelf($args->id);
		
		Session::set('edit-user-values', $request);

		if (empty($request->name)) {
			Session::set('edit-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Nome"'
			]);
		}
		elseif (empty($request->document)) {
			Session::set('edit-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Documento"'
			]);
		}
		elseif (empty($request->document_type)) {
			Session::set('edit-user', [
				'type' => 'alert-warning',
				'text' => 'Preencha o campo "Tipo de documento"'
			]);
		}
		elseif ($request->confirm != $request->password) {
			Session::set('edit-user', [
				'type' => 'alert-warning',
				'text' => 'Os campos "Senha" e "Confirmar senha" devem possuir o mesmo valor'
			]);
		}
		else {
			$member = Member::get($args->id);
			if ($member !== false) {
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
					Session::set('edit-user', [
						'type' => 'alert-success',
						'text' => 'Calebe atualizado com sucesso'
					]);
				} else {
					Session::set('edit-user', [
						'type' => 'alert-warning',
						'text' => 'Erro ao atualizar cadastro'
					]);
				}
			} else {
				Session::set('edit-user', [
					'type' => 'alert-danger',
					'text' => 'Usuário não encontrado'
				]);
			}
		}

		Router::redirect('membros', $args->id, 'editar');
	}

	public function delete($args)
	{
		$this->checkLeader();
		$member = Member::get($args->id);
		if ($member !== false) {
			$member->delete();
		}
		Router::redirect('membros');
	}

	public function addLeader($args)
	{
		$this->checkLeader();
		$member = Member::get($args->id);

		if ($member !== false) {
			$member->addLeader();
		}
		Router::redirect('membros');
	}

	public function removeLeader($args)
	{
		$this->checkLeader();
		$member = Member::get($args->id);

		if ($member !== false) {
			$member->removeLeader();
		}
		Router::redirect('membros');
	}

	public function addPoint($args)
	{
		$this->checkLeader();
		$user = Session::get('user');
		$member = Member::get($args->id);
		$point = $user->getPoint();

		if ($member !== false && $point !== false) {
			$member->setPoint($point);
			$member->update();
		}
		Router::redirect('membros');
	}

	public function removePoint($args)
	{
		$this->checkLeader();
		$member = Member::get($args->id);
		
		if ($member !== false) {
			$member->setPoint('');
			$member->update();
		}
		Router::redirect('membros');
	}
}