<?php

namespace App\Controllers;

use Tonight\Tools\Session;
use Tonight\MVC\Router;
use App\Models\Search;
use App\Models\Question;
use App\Models\Option;

class OptionController extends BaseController
{
	public function index() {}

	public function add($args, $request)
	{
		$search = Search::get($args->search);

		if ($search === false) {
			Router::redirect();
			exit;
		}

		$question = Question::get($search->getId(), $args->question);

		if ($question === false) {
			Router::redirect();
			exit;
		}

		$this->checkLeaderAndPoint($search->getPoint());
		$text = $request->get('text', '');
		$insert = $request->insert;
		
		$options = $question->getOptions();

		if (count($options)) {
			$last = array_pop($options);
			$number = $last->getNumber() + 1;
		} else {
			$number = 1;
		}

		$option = new Option();
		$option->setSearch($search->getId());
		$option->setQuestionNumber($question->getNumber());
		$option->setNumber($number);
		$option->setText($text);
		$option->setInsert($insert);

		if ($option->insert() === false) {
			Session::setFlash('edit-question', [
				'type' => 'alert-danger',
				'text' => 'Esta pergunta já possui uma resposta com esse número'
			]);
		} else {
			Session::setFlash('edit-question', [
				'type' => 'alert-info',
				'text' => 'Resposta adicionada'
			]);
		}
		Router::redirect(
			'pesquisa', $search->getId(), 'pergunta', $question->getNumber(), 'editar'
		);
	}

	public function remove($args)
	{
		$search = Search::get($args->search);

		if ($search === false) {
			Router::redirect();
			exit;
		}

		$question = Question::get($search->getId(), $args->question);

		if ($question === false) {
			Router::redirect();
			exit;
		}

		$option = Option::get($search->getId(), $question->getNumber(), $args->option);

		if ($option === false) {
			Router::redirect();
			exit;
		}

		if ($option->delete() !== false) {
			Session::setFlash('edit-question', [
				'type' => 'alert-info',
				'text' => 'Resposta excluída'
			]);
		} else {
			Session::setFlash('edit-question', [
				'type' => 'alert-danger',
				'text' => 'Erro ao excluir resposta'
			]);
		}

		Router::redirect(
			'pesquisa', $search->getId(), 'pergunta', $question->getNumber(), 'editar'
		);
	}

	public function edit($args)
	{
		$search = Search::get($args->search);

		if ($search === false) {
			Router::redirect();
			exit;
		}

		$question = Question::get($search->getId(), $args->question);

		if ($question === false) {
			Router::redirect();
			exit;
		}

		$option = Option::get($search->getId(), $question->getNumber(), $args->option);

		if ($option === false) {
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
				'pesquisa', $search->getId(),
				'pergunta', $question->getNumber(),
				'resposta', $option->getNumber(),
				'acao/editar'
			)
		);
		$this->setVariable('images', Router::getLink('assets/images'));
		$this->setVariable('add_options', true);
		$this->setVariable('options', $question->getOptions());
		if (Session::issetFlash('edit-question-values')) {
			$this->setVariable('form', Session::getFlash('edit-question-values'));
		} else {
			$form = new \stdClass;
			$form->title = $question->getTitle();
			$form->text = $question->getText();
			$form->type = $question->getType();
			$this->setVariable('form', $form);
		}
		if (Session::issetFlash('edit-option-values')) {
			$this->setVariable('form', Session::getFlash('edit-option-values'));
		} else {
			$opt = new \stdClass;
			$opt->text = $option->getText();
			$opt->insert = $option->getInsert();
			$this->setVariable('opt', $opt);
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

		$question = Question::get($search->getId(), $args->question);

		if ($question === false) {
			Router::redirect();
			exit;
		}

		$option = Option::get($search->getId(), $question->getNumber(), $args->option);

		if ($option === false) {
			Router::redirect();
			exit;
		}

		$this->checkLeaderAndPoint($search->getPoint());
		$text = $request->get('text', '');
		$insert = $request->insert;
		
		$option->setText($text);
		$option->setInsert($insert);

		if ($option->update() === false) {
			Session::setFlash('edit-question', [
				'type' => 'alert-danger',
				'text' => 'Erro ao editar resposta'
			]);
		} else {
			Session::setFlash('edit-question', [
				'type' => 'alert-info',
				'text' => 'Resposta editada'
			]);
		}
		Router::redirect(
			'pesquisa', $search->getId(), 'pergunta', $question->getNumber(), 'editar'
		);
	}

	public function moveUp($args)
	{
		$search = Search::get($args->search);

		if ($search === false) {
			Router::redirect();
			exit;
		}

		$question = Question::get($search->getId(), $args->question);

		if ($question === false) {
			Router::redirect();
			exit;
		}

		$option = Option::get($search->getId(), $question->getNumber(), $args->option);

		if ($option === false) {
			Router::redirect();
			exit;
		}

		$this->checkLeaderAndPoint($search->getPoint());

		$options = $question->getOptions();

		if (count($options)) {
			$before = Option::get($search->getId(), $question->getNumber(), $option->getNumber() - 1);

			if ($before !== false) {
				$b_number = $option->getNumber();
				$q_number = $before->getNumber();

				$before->setNumber($b_number);
				$before->update();

				$option->setNumber($q_number);
				$option->update();
			}
		}

		Router::redirect(
			'pesquisa', $search->getId(), 'pergunta', $question->getNumber(), 'editar'
		);
	}

	public function moveDown($args)
	{
		$search = Search::get($args->search);

		if ($search === false) {
			Router::redirect();
			exit;
		}

		$question = Question::get($search->getId(), $args->question);

		if ($question === false) {
			Router::redirect();
			exit;
		}

		$option = Option::get($search->getId(), $question->getNumber(), $args->option);

		if ($option === false) {
			Router::redirect();
			exit;
		}

		$this->checkLeaderAndPoint($search->getPoint());

		$options = $question->getOptions();

		if (count($options)) {
			$after = Option::get($search->getId(), $question->getNumber(), $option->getNumber() + 1);

			if ($after !== false) {
				$a_number = $option->getNumber();
				$q_number = $after->getNumber();

				$after->setNumber($a_number);
				$after->update();

				$option->setNumber($q_number);
				$option->update();
			}
		}

		Router::redirect(
			'pesquisa', $search->getId(), 'pergunta', $question->getNumber(), 'editar'
		);
	}
}