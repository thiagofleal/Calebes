<?php

namespace App\Controllers;

use stdClass;
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
		$this->setVariable('action', Router::getLink(
			'pesquisas', $search->getId(), 'perguntas/acao/adicionar'
		));
		$this->setVariable('add_options', false);
		$this->setVariable('options', array());
		$this->setVariable('link_questions', Router::getLink('pesquisas', $search->getId(), 'editar'));
		if (Session::isset('register-question-values')) {
			$this->setVariable('form', Session::getFlash('register-question-values'));
		} else {
			$this->setVariable('form', new stdClass);
		}
		if (Session::isset('register-question')) {
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
			Session::set('register-question', [
				'type' => 'alert-danger',
				'text' => 'O campo de texto não pode ser vazio'
			]);
			Router::redirect('pesquisas', $search->getId(), 'perguntas/adicionar');
			exit;
		}

		$question = new Question();
		$question->setTitle($title);
		$question->setText($text);
		$question->setType($type);
		$question->setNumber($last_question + 1);
		if ($search->addQuestion($question)) {
			Session::set('edit-question', [
				'type' => 'alert-success',
				'text' => 'Pergunta inserida com sucesso'
			]);
		} else {
			Session::set('edit-question', [
				'type' => 'alert-danger',
				'text' => 'Erro ao inserir pergunta'
			]);
		}
		Router::redirect('pesquisas', $search->getId(), 'perguntas', $question->getNumber(), 'editar');
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
				'pesquisas', $search->getId(), 'perguntas', $question->getNumber(), 'acao/editar'
			)
		);
		$this->setVariable(
			'action_option', Router::getLink(
				'pesquisas', $search->getId(), 'perguntas', $question->getNumber(), 'alternativas/acao/adicionar'
			)
		);
		$this->setVariable('images', Router::getLink('assets/images'));
		$this->setVariable('add_options', true);
		$this->setVariable('options', $question->getOptions());
		$this->setVariable('link_questions', Router::getLink('pesquisas', $search->getId(), 'editar'));
		if (Session::isset('edit-question-values')) {
			$this->setVariable('form', Session::getFlash('edit-question-values'));
		} else {
			$form = new stdClass;
			$form->title = $question->getTitle();
			$form->text = $question->getText();
			$form->type = $question->getType();
			$this->setVariable('form', $form);
		}
		if (Session::isset('edit-question')) {
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
			Session::set('edit-question', [
				'type' => 'alert-danger',
				'text' => 'O campo de texto não pode ser vazio'
			]);
			Router::redirect(
				'pesquisas', $search->getId(), 'perguntas', $question->getNumber(), 'editar'
			);
			exit;
		}

		$question->setTitle($title);
		$question->setText($text);
		$question->setType($type);

		if ($question->update() === false) {
			Session::set('edit-question', [
				'type' => 'alert-danger',
				'text' => 'Erro ao alterar pergunta'
			]);
		} else {
			Session::set('edit-question', [
				'type' => 'alert-success',
				'text' => 'Pergunta alterada com sucesso'
			]);
		}
		Router::redirect('pesquisas', $search->getId(), 'perguntas', $question->getNumber(), 'editar');
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
			Session::set('edit-search', [
				'type' => 'alert-info',
				'text' => 'Pergunta excluída'
			]);
		} else {
			Session::set('edit-search', [
				'type' => 'alert-danger',
				'text' => 'Erro ao excluir pergunta'
			]);
		}
		Router::redirect(
			'pesquisas', $search->getId(), 'editar'
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
			'pesquisas', $search->getId(), 'editar'
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
			'pesquisas', $search->getId(), 'editar'
		);
	}
}