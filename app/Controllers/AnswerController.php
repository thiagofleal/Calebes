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

		$answer = new Answer();
		$search_answers = $request->get('answer', ['value' => array(), 'extra' => array()]);

		$answer->setUser($user);
		$search->addAnswer($answer);

		$answers = $search->getAnswers();
		$answer = array_pop($answers);

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

		Router::redirect();
	}

	public function results($args)
	{
		$search = Search::get($args->id);

		if ($search === false) {
			Router::redirect();
		}

		$this->checkLeaderAndPoint($search->getPoint());

		$answers = $search->getAnswers();
		$count = count($answers);
		$results = array();

		foreach ($search->getQuestions() as $question) {

			$results[$question->getNumber()]['text'] = $question->getText();
			$results[$question->getNumber()]['options'] = array();

			foreach ($question->getOptions() as $option) {
				$count_option = $search->countAnswerOption($option);
				$results[$question->getNumber()]['options'][$option->getNumber()]['text'] =
					$option->getText();
				$results[$question->getNumber()]['options'][$option->getNumber()]['count'] =
					$count_option;
				$results[$question->getNumber()]['options'][$option->getNumber()]['data'] =
					$option->getSelected();
				if ($count) {
					$results[$question->getNumber()]['options'][$option->getNumber()]['statistics'] =
						(floatval($count_option) / floatval($count)) * 100.0;
				} else {
					$results[$question->getNumber()]['options'][$option->getNumber()]['statistics'] =
						0;
				} 
			}
		}

		$this->setVariable('title', "Resultados");
		$this->setVariable('search', $search);
		$this->setVariable('count', $count);
		$this->setVariable('results', $results);
		$this->render('search-results', 'main-template');
	}
}