<?php

namespace App\Controllers;

use Tonight\MVC\Router;
use Tonight\Tools\Session;
use App\Models\Search;
use App\Models\Question;
use App\Models\Answer;
use App\Models\SelectedOption;

class AnswerController extends BaseController
{
	public function action($args, $request)
	{
		$user = $this->checkLogged();
		$search = Search::get($args->id);

		$this->checkPoint($search->getPoint());

		if (!$search->isVisible()) {
			$this->checkLeader();
		}

		$answer = new Answer();
		$search_answers = $request->get('answer', ['value' => array(), 'extra' => array()]);

		$answer->setUser($user);
		$search->addAnswer($answer);

		foreach ($search_answers["value"] as $question_number => $question_options) {
			$question = $search->getQuestion($question_number);
			
			foreach ($question_options as $option_number => $option_value) {
				$option = $question->getOption($option_value);
				$mark = new SelectedOption();
				$mark->setOption($option);

				if (isset($search_answers['extra'][$question_number][$option_value])) {
					$mark->setText($search_answers['extra'][$question_number][$option_value]);
				}

				$answer->addOption($mark);
			}
		}

		Session::set('open-search', [
			'type' => "alert-info",
			'text' => "Resposta adicionada ao banco de dados com sucesso"
		]);

		Router::redirect('pesquisas', $search->getId(), 'abrir');
	}

	public function results($args, $request)
	{
		$search = Search::get($args->id);

		if ($search === false) {
			Router::redirect();
		}

		$filter = array();

		$this->checkLeaderAndPoint($search->getPoint());

		$this->setVariable('title', $search->getName());
		$this->setVariable('search', $search);

		if (isset($request->user)) {
			$filter['name'] = $request->user ?? '';
		}

		$this->setVariable('filter_action',
			Router::getLink('pesquisas', $search->getId(), 'resultados')
		);
		$this->setVariable('answers', $search->getAnswersFilter($filter));
		$this->setVariable('general_link', Router::getLink('pesquisas', $search->getId(), 'resultados/geral'));
		$this->render('list-results', 'main-template');
	}

	public function generalResults($args)
	{
		$search = Search::get($args->id);

		if ($search === false) {
			Router::redirect();
		}

		$this->checkLeaderAndPoint($search->getPoint());

		$answers = $search->getAnswers();
		$count = count($answers);
		
		$this->setVariable('title', "Resultados");
		$this->setVariable('search', $search);
		$this->setVariable('count', $count);
		$this->render('search-results', 'main-template');
	}

	public function unitaryResult($args)
	{
		$search = Search::get($args->search);

		if ($search === false) {
			Router::redirect();
		}

		$this->checkLeaderAndPoint($search->getPoint());

		$answer = Answer::get($args->answer);

		if ($answer === false) {
			Router::redirect();
		}

		$this->setVariable('title', $search->getName());
		$this->setVariable('search', $search);
		$this->setVariable('answer', $answer);
		$this->render('answer-view', 'main-template');
	}

	public function delete($args)
	{
		$search = Search::get($args->search);

		if ($search === false) {
			Router::redirect();
		}

		$this->checkLeaderAndPoint($search->getPoint());

		$answer = Answer::get($args->answer);

		if ($answer === false) {
			Router::redirect();
		}

		$answer->delete();
		Router::redirect('pesquisas', $search->getId(), 'resultados');
	}
}