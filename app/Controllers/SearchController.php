<?php

namespace App\Controllers;

use stdClass;
use Tonight\MVC\Router;
use Tonight\Tools\Session;
use App\Models\Point;
use App\Models\Search;
use App\Models\Question;

class SearchController extends BaseController
{
	public function index()
	{
		$this->checkLeader();
		$this->setVariable('title', 'Listar pesquisas');
		$user = Session::get('user');
		$point = $user->getPoint();

		if ($point === false) {
			$researches = array();
		} else {
			$researches = $point->getResearches();
		}

		$this->setVariable('researches', $researches);
		$this->render('list-researches', 'main-template');
	}

	public function register()
	{
		$this->checkLeader();
		$this->setVariable('title', 'Criar pesquisa');
		$this->setVariable('action', Router::getLink('pesquisas/acao/cadastrar'));
		$this->setVariable('add_questions', false);
		$this->setVariable('form', new stdClass);
		$this->setVariable('questions', array());
		if (Session::issetFlash('register-search')) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', Session::getFlash('register-search'));
		} else {
			$this->setVariable('flash', false);
			$this->setVariable('alert', array(
				'type' => '',
				'text' => ''
			));
		}
		$this->render('form-search', 'main-template');
	}

	public function registerAction($request)
	{
		$this->checkLeader();
		$search = new Search();
		$user = Session::get('user');
		$point = $user->getPoint();

		if ($point === false) {
			Session::setFlash('register-search', [
				'type' => 'alert-danger',
				'text' => 'É necessário estar alocado em um ponto para criar uma pesquisa'
			]);
		} else {
			$search->setName($request->get('name', ''));
			if ($point->addSearch($search)) {
				Session::setFlash('edit-search', [
					'type' => 'alert-success',
					'text' => 'Pesquisa cadastrada com sucesso'
				]);
				$researches = $point->getResearches();
				$last = array_shift($researches);
				Router::redirect('pesquisas', $last->getId(), 'editar');
				exit;
			}
		}
		Router::redirect('pesquisas/cadastrar');
	}

	public function edit($args)
	{
		$search = Search::get($args->id);

		if ($search === false) {
			Router::redirect();
		}

		$this->checkLeaderAndPoint($search->getPoint());
		
		$this->setVariable('title', 'Editar pesquisa');
		$this->setVariable('action', Router::getLink('pesquisas', $args->id, 'acao/editar'));
		$this->setVariable('images', Router::getLink('assets/images'));
		$this->setVariable('view_link', Router::getLink('pesquisas', $search->getId(), 'abrir'));
		
		if ($search->isVisible()) {
			$this->setVariable('release', false);
			$this->setVariable('release_link', Router::getLink('pesquisas', $search->getId(), 'bloquear'));
		} else {
			$this->setVariable('release', true);
			$this->setVariable('release_link', Router::getLink('pesquisas', $search->getId(), 'liberar'));
		}
		
		$this->setVariable('add_questions', true);
		$this->setVariable('add_question_link', Router::getLink(
			'pesquisas', $args->id, 'perguntas/adicionar'
		));
		
		if ($search === false) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', [
				'type' => 'alert-danger',
				'text' => 'Pesquisa não encontrada'
			]);
			exit;
		} else {
			$form = new stdClass;
			$form->name = $search->getName();
		}
		
		$this->setVariable('form', $form);
		$this->setVariable('questions', $search->getQuestions());
		if (Session::issetFlash('edit-search')) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', Session::getFlash('edit-search'));
		} else {
			$this->setVariable('flash', false);
			$this->setVariable('alert', [
				'type' => '',
				'text' => ''
			]);
		}
		$this->render('form-search', 'main-template');
	}

	public function editAction($args, $request)
	{
		$search = Search::get($args->id);

		if ($search === false) {
			Router::redirect();
		}

		$this->checkLeaderAndPoint($search->getPoint());
		
		if ($search === false) {
			Session::setFlash('edit-search', [
				'type' => 'alert-danger',
				'text' => 'Pesquisa não encontrada'
			]);
		} else {
			$user = Session::get('user');
			$search->setPoint($user->getPoint());
			$search->setName($request->get('name', ''));
			$search->update();
			Session::setFlash('edit-search', [
				'type' => 'alert-success',
				'text' => 'Pesquisa atualizada com sucesso'
			]);
		}
		Router::redirect('pesquisas', $args->id, 'editar');
	}

	public function delete($args)
	{
		$this->checkLeader();
		$search = Search::get($args->id);
		if ($search === false) {
			Session::setFlash('delete-search', [
				'type' => 'alert-danger',
				'text' => 'Pesquisa não encontrada'
			]);
		} else {
			$user = Session::get('user');
			if ($search->delete() !== false) {
				Session::setFlash('delete-search', [
					'type' => 'alert-success',
					'text' => 'Pesquisa excluída com sucesso'
				]);
			} else {
				Session::setFlash('delete-search', [
					'type' => 'alert-danger',
					'text' => 'Erro ao excluir pesquisa'
				]);
			}
		}
		Router::redirect('pesquisas');
	}

	public function open($args)
	{
		$search = Search::get($args->id);

		if ($search === false) {
			Router::redirect();
		}

		$user = $this->checkLogged();
		$this->checkPoint($search->getPoint());

		if (! $search->isVisible()) {
			$this->checkLeader();
		} else {
			$this->token($search);
		}

		$this->setVariable('title', $search->getName());
		$this->setVariable('questions', $search->getQuestions());
		$this->setVariable('action', Router::getLink('pesquisas', $search->getId(), 'acao/responder'));
		
		if (Session::issetFlash('open-search')) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', Session::getFlash('open-search'));
		} else {
			$this->setVariable('flash', false);
			$this->setVariable('alert', [
				'type' => '',
				'text' => ''
			]);
		}
		$this->render('search-view', 'main-template');
	}

	public function release($args)
	{
		$search = Search::get($args->id);

		if ($search === false) {
			Router::redirect();
			exit();
		}

		$this->checkLeaderAndPoint($search->getPoint());
		$search->createToken();
		$search->update();
		Router::redirect('pesquisas', $search->getId(), 'editar');
	}

	public function block($args)
	{
		$search = Search::get($args->id);

		if ($search === false) {
			Router::redirect();
			exit();
		}

		$this->checkLeaderAndPoint($search->getPoint());
		$search->removeToken();
		$search->update();
		Router::redirect('pesquisas', $search->getId(), 'editar');
	}

	private function token($search)
	{
		if (! Session::isset('search-token')) {
			$this->setVariable('title', "Verificar token");

			if (Session::issetFlash('form-token')) {
				$this->setVariable('flash', true);
				$this->setVariable('alert', Session::getFlash('form-token'));
			} else {
				$this->setVariable('flash', false);
				$this->setVariable('alert', [
					'type' => '',
					'text' => ''
				]);
			}
			$this->setVariable('action', Router::getLink('pesquisas', $search->getId(), 'token'));
			$this->render('form-token', 'main-template');
			exit();
		}

		if ($search->validateToken(Session::get('search-token'))) {
			return;
		} else {
			$search_access = Session::get('search-token');
			$search_access[$search->getId()] = false;
			Session::set('search-token', $search_access);
			Session::setFlash('form-token', [
				'type' => 'alert-danger',
				'text' => 'Token incorreto'
			]);
			Session::unset('search-token');
			Router::redirect('pesquisas', $search->getId(), 'abrir');
			exit();
		}
	}

	public function saveToken($args, $request)
	{
		$token = $request->get('token', NULL);
		Session::set('search-token', $token);
		Router::redirect('pesquisas', $args->id, 'abrir');
	}
}