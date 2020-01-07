<?php

namespace App\Controllers;

use Tonight\MVC\Router;
use Tonight\Tools\Session;
use App\Models\Search;
use App\Models\Question;

class SearchController extends BaseController
{
	public function index()
	{
		$this->checkLeader();
		$this->setVariable('title', 'Listar pesquisas');
		$user = Session::get('user');
		$this->setVariable('researches', Search::getAllFromPoint($user->getPoint()));
		$this->render('list-researches', 'main-template');
	}

	public function register()
	{
		$this->checkLeader();
		$this->setVariable('title', 'Criar pesquisa');
		$this->setVariable('action', Router::getLink('pesquisa/acao/cadastrar'));
		$this->setVariable('add_questions', false);
		$this->setVariable('form', new \stdClass);
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
		$search->setPoint($user->getPoint());
		$search->setName($request->get('name', ''));
		$search->insert();
		Session::setFlash('register-search', [
			'type' => 'alert-success',
			'text' => 'Pesquisa cadastrada com sucesso'
		]);
		Router::redirect('pesquisa/cadastrar');
	}

	public function edit($args)
	{
		$this->checkLeader();
		$this->setVariable('title', 'Criar pesquisa');
		$this->setVariable('action', Router::getLink('pesquisa', $args->id, 'acao/editar'));
		$this->setVariable('add_questions', true);
		$search = Search::get($args->id);
		if ($search === false) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', [
				'type' => 'alert-danger',
				'text' => 'Pesquisa não encontrada'
			]);
			exit;
		} else {
			$form = new \stdClass;
			$form->name = $search->getName();
		}
		$this->setVariable('form', $form);
		$this->setVariable('questions', Question::getAllFromSearch($search->getId()));
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
		$this->checkLeader();
		$search = Search::get($args->id);
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
		Router::redirect('pesquisa', $args->id, 'editar');
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
}