<?php

namespace App\Controllers;

use Tonight\Tools\Session;
use Tonight\MVC\Router;
use App\Models\Search;
use App\Models\Question;

class QuestionController extends BaseController
{
	public function index() {}

	public function create($args)
	{
		$search = Search::get($args->search);

		if ($search === false) {
			Router::redirect();
			exit;
		}

		$this->checkLeaderAndPoint($search->getPoint());
		$this->setVariable('title', 'Adicionar pergunta');
		$this->setVariable('action', Router::getLink('pesquisa', $search->getId(), 'pergunta/acao/adicionar'));
		$this->setVariable('add_options', false);
		$this->setVariable('options', array());
		$this->setVariable('link_questions', Router::getLink('pesquisa', $search->getId(), 'editar'));
		if (Session::issetFlash('register-question-values')) {
			$this->setVariable('form', Session::getFlash('register-question-values'));
		} else {
			$this->setVariable('form', new \stdClass);
		}
		if (Session::issetFlash('register-question')) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', Session::getFlash('register-question'));
		} else {
			$this->setVariable('flash', false);
			$this->setVariable('alert', [
				'type' => '',
				'text' => ''
			]);
		}
		$this->render('form-question', 'main-template');
	}

	public function createAction($args, $request)
	{
		$search = Search::get($args->search);

		if ($search === false) {
			Router::redirect();
			exit;
		}
		
		$this->checkLeaderAndPoint($search->getPoint());
		
		$last_question = $search->getQuestions();
		$last_question = array_pop($last_question);
		$last_question = $last_question ? $last_question->getNumber() : 0;
		$title = $request->get('title', '');
		$text = $request->get('text', '');
		$type = $request->get('type', '');

		if (empty($text)) {
			Session::setFlash('register-question', [
				'type' => 'alert-danger',
				'text' => 'O campo de texto não pode ser vazio'
			]);
			Router::redirect('pesquisa', $search->getId(), 'pergunta/adicionar');
			exit;
		}

		$question = new Question();
		$question->setTitle($title);
		$question->setText($text);
		$question->setType($type);
		$question->setSearch($search->getId());
		$question->setNumber($last_question + 1);
		$question->insert();
		Session::setFlash('edit-question', [
			'type' => 'alert-success',
			'text' => 'Pergunta inserida com sucesso'
		]);
		Router::redirect('pesquisa', $search->getId(), 'pergunta', $question->getNumber(), 'editar');
	}

	public function edit($args)
	{
		$search = Search::get($args->search);

		if ($search === false) {
			Router::redirect();
			exit;
		}

		$question = $search->getQuestion($args->question);

		if ($question === false) {
			Router::redirect();
			exit;
		}

		$this->checkLeaderAndPoint($search->getPoint());
		$this->setVariable('title', 'Editar pergunta');
		$this->setVariable(
			'action', Router::getLink(
				'pesquisa', $search->getId(), 'pergunta', $question->getNumber(), 'acao/editar'
			)
		);
		$this->setVariable(
			'action_option', Router::getLink(
				'pesquisa', $search->getId(), 'pergunta', $question->getNumber(), 'resposta/acao/adicionar'
			)
		);
		$this->setVariable('images', Router::getLink('assets/images'));
		$this->setVariable('add_options', true);
		$this->setVariable('options', $question->getOptions());
		$this->setVariable('link_questions', Router::getLink('pesquisa', $search->getId(), 'editar'));
		if (Session::issetFlash('edit-question-values')) {
			$this->setVariable('form', Session::getFlash('edit-question-values'));
		} else {
			$form = new \stdClass;
			$form->title = $question->getTitle();
			$form->text = $question->getText();
			$form->type = $question->getType();
			$this->setVariable('form', $form);
		}
		if (Session::issetFlash('edit-question')) {
			$this->setVariable('flash', true);
			$this->setVariable('alert', Session::getFlash('edit-question'));
		} else {
			$this->setVariable('flash', false);
			$this->setVariable('alert', [
				'type' => '',
				'text' => ''
			]);
		}
		$this->render('form-question', 'main-template');
	}

	public function editAction($args, $request)
	{
		$search = Search::get($args->search);

		if ($search === false) {
			Router::redirect();
			exit;
		}

		$question = $search->getQuestion($args->question);

		if ($question === false) {
			Router::redirect();
			exit;
		}

		$this->checkLeaderAndPoint($search->getPoint());
		$title = $request->get('title', '');
		$text = $request->get('text', '');
		$type = $request->get('type', '');

		if (empty($text)) {
			Session::setFlash('edit-question', [
				'type' => 'alert-danger',
				'text' => 'O campo de texto não pode ser vazio'
			]);
			Router::redirect(
				'pesquisa', $search->getId(), 'pergunta', $question->getNumber(), 'editar'
			);
			exit;
		}

		$question->setTitle($title);
		$question->setText($text);
		$question->setType($type);

		if ($question->update() === false) {
			Session::setFlash('edit-question', [
				'type' => 'alert-danger',
				'text' => 'Erro ao alterar pergunta'
			]);
		} else {
			Session::setFlash('edit-question', [
				'type' => 'alert-success',
				'text' => 'Pergunta alterada com sucesso'
			]);
		}
		Router::redirect('pesquisa', $search->getId(), 'pergunta', $question->getNumber(), 'editar');
	}

	public function delete($args)
	{
		$search = Search::get($args->search);

		if ($search === false) {
			Router::redirect();
			exit;
		}

		$question = $search->getQuestion($args->question);

		if ($question === false) {
			Router::redirect();
			exit;
		}

		$this->checkLeaderAndPoint($search->getPoint());
		if ($question->delete() !== false) {
			Session::setFlash('edit-search', [
				'type' => 'alert-info',
				'text' => 'Pergunta excluída'
			]);
		} else {
			Session::setFlash('edit-search', [
				'type' => 'alert-danger',
				'text' => 'Erro ao excluir pergunta'
			]);
		}
		Router::redirect(
			'pesquisa', $search->getId(), 'editar'
		);
	}

	public function moveUp($args)
	{
		$search = Search::get($args->search);

		if ($search === false) {
			Router::redirect();
			exit;
		}

		$question = $search->getQuestion($args->question);

		if ($question === false) {
			Router::redirect();
			exit;
		}

		$this->checkLeaderAndPoint($search->getPoint());

		$questions = $search->getQuestions();

		if (count($questions)) {
			$before = $search->getQuestion($question->getNumber() - 1);

			if ($before !== false) {
				$b_number = $question->getNumber();
				$q_number = $before->getNumber();

				$before->setNumber($b_number);
				$before->update();

				$question->setNumber($q_number);
				$question->update();
			}
		}

		Router::redirect(
			'pesquisa', $search->getId(), 'editar'
		);
	}

	public function moveDown($args)
	{
		$search = Search::get($args->search);

		if ($search === false) {
			Router::redirect();
			exit;
		}

		$question = $search->getQuestion($args->question);

		if ($question === false) {
			Router::redirect();
			exit;
		}

		$this->checkLeaderAndPoint($search->getPoint());

		$questions = $search->getQuestions();

		if (count($questions)) {
			$after = $search->getQuestion($question->getNumber() + 1);

			if ($after !== false) {
				$a_number = $question->getNumber();
				$q_number = $after->getNumber();

				$after->setNumber($a_number);
				$after->update();

				$question->setNumber($q_number);
				$question->update();
			}
		}

		Router::redirect(
			'pesquisa', $search->getId(), 'editar'
		);
	}
}