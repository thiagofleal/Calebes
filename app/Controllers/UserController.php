<?php

namespace App\Controllers;

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
		$point = Point::get($user->getPoint());
		$this->setVariable('title', 'Gerenciar calebes');
		$members_point = $point->getMembers();
		$members_null = Member::getNotPoint();
		$this->setVariable('members', array_merge($members_point, $members_null));
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
			$user = Session::get('user');
			$member->setPoint($user->getPoint());

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
			$member = Member::get($args->id);
			if ($member !== false) {
				$form = new \stdClass;
				$form->name = $member->getName();
				$form->birth = $member->getBirth();
				$form->address = $member->getAddress();
				$form->phone = $member->getPhone();
				$form->email = $member->getEmail();
				$form->tshirt_size = $member->getTshirt_size();
				$form->document = $member->getDocument();
				$form->document_type = $member->getDocument_type();
			} else {
				$form = new \stdClass;
			}
		}

		$this->setVariable('form', $form);
		if (Session::issetFlash('edit-user')) {
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
			} else {
				Session::setFlash('edit-user', [
					'type' => 'alert-danger',
					'text' => 'Usuário não encontrado'
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