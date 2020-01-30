<?php

namespace App\Models;

class Search
{
	private $id;
	private $name;
	private $point;
	private $creation;
	private $token;

	public static function get($id)
	{
		$search = new self();
		if ($search->load($id)) {
			return $search;
		} else {
			return false;
		}
	}

	public function __construct() {}

	public function getId() { return $this->id; }

	public function getName() { return $this->name; }
	public function setName($name) { $this->name = $name; }

	public function getPoint()
	{
		return Point::get($this->point);
	}

	public function setPoint($point)
	{
		if ($point instanceof Point) {
			$this->point = $point->getId();
		} else {
			$this->point = $point;
		}
	}

	public function getCreation() { return $this->creation; }
	public function setCreation($creation) { $this->creation = $creation; }

	public function getToken()
	{
		return substr($this->token, 0, 7);
	}
	
	public function createToken()
	{
		$this->token = md5($this->id.time().$this->point);
	}
	
	public function removeToken()
	{
		$this->token = NULL;
	}

	public static function getAll()
	{
		$db = new DataBase('search');
		$ret = array();
		foreach ($db->search as $value) {
			$ret[] = self::get($value->id);
		}
		return $ret;
	}

	public function getQuestions()
	{
		$db = new DataBase('question');
		$ret = array();
		foreach ($db->question->where( function($row) {
			return $row->search == $this->id;
		})->order( function($a, $b) {
			return intval($a->number) - intval($b->number);
		}) as $value) {
			$ret[] = Question::get($value->id);
		}
		return $ret;
	}

	public function getQuestion($number)
	{
		$db = new DataBase('question');

		$result = $db->question->where( function($row) use($number){
			return $row->search == $this->id && $row->number == $number;
		});

		if ($result->size() == 0) {
			return false;
		}

		return Question::get($result->get(0)->id);
	}

	public function addQuestion(Question $question)
	{
		if ($question) {
			$question->setSearch($this);
			$question->insert();
			return true;
		}
		return false;
	}

	public function getAnswers()
	{
		$db = new DataBase('answer');
		$ret = array();
		foreach ($db->answer->where( function($row) {
			return $row->search == $this->id;
		})->order( function($a, $b) {
			return strtotime($b->time) - strtotime($a->time);
		}) as $value) {
			$ret[] = Answer::get($value->id);
		}
		return $ret;
	}

	public function getAnswersFilter(array $filter)
	{
		$db = new DataBase(['answer', 'member']);
		$ret = array();
		
		foreach ($db->answer->select( function($row) {
			$row->answer = $row->id;
			return $row;
		})->join($db->member, function($answer, $member) {
			return $answer->user == $member->id;
		})->where( function($row) {
			return $row->search == $this->id;
		})->where( function($row) use($filter) {
			foreach ($filter as $key => $value) {
				if (! empty($value)) {
					if (strpos($row->{$key}, $value) === false) {
						return false;
					}
				}
			}
			return true;
		}) as $value) {
			$ret[] = Answer::get($value->answer);
		}
		return $ret;
	}

	public function addAnswer(Answer $answer)
	{
		if ($answer) {
			$answer->setSearch($this);
			$answer->insert();
			return true;
		}
		return false;
	}

	public function countAnswerOption($option)
	{
		$db = new DataBase('selected_option');

		if ($option instanceof Option) {
			$option_id = $option->getId();
		} else {
			$option_id = $option;
		}

		$result = $db->selected_option->where( function($row) use($option_id) {
			return $row->option == $option_id;
		});

		return $result->size();
	}

	public function isVisible()
	{
		return !empty($this->token);
	}

	public function validateToken($token)
	{
		return $token == $this->getToken();
	}

	public function insert()
	{
		$db = new DataBase('search');

		$db->search->append([
			'point' => $this->point,
			'token' => $this->token,
			'name' => $this->name
		]);
		$db->search->update();
	}

	public function load($id)
	{
		$db = new DataBase('search');

		$result = $db->search->where( function($row) use($id) {
			return $row->id == $id;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);

		foreach ($result as $key => $value) {
			$this->{$key} = $result->{$key};
		}

		return true;
	}

	public function update()
	{
		$db = new DataBase('search');

		$result = $db->search->where( function($row) {
			return $row->id == $this->id;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);

		foreach ($result as $key => $value) {
			$result->{$key} = $this->{$key};
		}
		
		$db->search->setValue($result);
		$db->search->update();

		return true;
	}

	public function delete()
	{
		$db = new DataBase('search');

		$result = $db->search->where( function($row) {
			return $row->id == $this->id;
		});

		if ($result->size() == 0) {
			return false;
		}

		foreach ($this->getQuestions() as $question) {
			if ($question->delete() === false) {
				return false;
			}
		}

		foreach ($this->getAnswers() as $answer) {
			if ($answer->delete() === false) {
				return false;
			}
		}

		$result = $result->get(0);

		$db->search->removeFirst($result);
		$db->search->update();

		return true;
	}
}