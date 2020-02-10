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

		$question = $search->getQuestion($args->question);

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
		$option->setNumber($number);
		$option->setText($text);
		$option->setInsert($insert);

		if ($question->addOption($option) === false) {
			Session::set('edit-question', [
				'type' => 'alert-danger',
				'text' => 'Esta pergunta já possui uma resposta com esse número'
			]);
		} else {
			Session::set('edit-question', [
				'type' => 'alert-info',
				'text' => 'Resposta adicionada'
			]);
		}
		Router::redirect(
			'pesquisas', $search->getId(), 'perguntas', $question->getNumber(), 'editar'
		);
	}

	public function remove($args)
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

		$option = $question->getOption($args->option);

		if ($option === false) {
			Router::redirect();
			exit;
		}

		if ($option->delete() !== false) {
			Session::set('edit-question', [
				'type' => 'alert-info',
				'text' => 'Resposta excluída'
			]);
		} else {
			Session::set('edit-question', [
				'type' => 'alert-danger',
				'text' => 'Erro ao excluir resposta'
			]);
		}

		Router::redirect(
			'pesquisas', $search->getId(), 'perguntas', $question->getNumber(), 'editar'
		);
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

		$option = $question->getOption($args->option);

		if ($option === false) {
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
				'pesquisas', $search->getId(),
				'perguntas', $question->getNumber(),
				'alternativas', $option->getNumber(),
				'acao/editar'
			)
		);
		$this->setVariable('images', Router::getLink('assets/images'));
		$this->setVariable('add_options', true);
		$this->setVariable('options', $question->getOptions());
		if (Session::isset('edit-question-values')) {
			$this->setVariable('form', Session::getFlash('edit-question-values'));
		} else {
			$form = new \stdClass;
			$form->title = $question->getTitle();
			$form->text = $question->getText();
			$form->type = $question->getType();
			$this->setVariable('form', $form);
		}
		if (Session::isset('edit-option-values')) {
			$this->setVariable('form', Session::getFlash('edit-option-values'));
		} else {
			$opt = new \stdClass;
			$opt->text = $option->getText();
			$opt->insert = $option->getInsert();
			$this->setVariable('opt', $opt);
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

		$option = $question->getOption($args->option);

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
			Session::set('edit-question', [
				'type' => 'alert-danger',
				'text' => 'Erro ao editar resposta'
			]);
		} else {
			Session::set('edit-question', [
				'type' => 'alert-info',
				'text' => 'Resposta editada'
			]);
		}
		Router::redirect(
			'pesquisas', $search->getId(), 'perguntas', $question->getNumber(), 'editar'
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

		$option = $question->getOption($args->option);

		if ($option === false) {
			Router::redirect();
			exit;
		}

		$this->checkLeaderAndPoint($search->getPoint());

		$options = $question->getOptions();

		if (count($options)) {
			$before = $question->getOption($option->getNumber() - 1);

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
			'pesquisas', $search->getId(), 'perguntas', $question->getNumber(), 'editar'
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

		$option = $question->getOption($args->option);

		if ($option === false) {
			Router::redirect();
			exit;
		}

		$this->checkLeaderAndPoint($search->getPoint());

		$options = $question->getOptions();

		if (count($options)) {
			$after = $question->getOption($option->getNumber() + 1);

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
			'pesquisas', $search->getId(), 'perguntas', $question->getNumber(), 'editar'
		);
	}
}