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
		$leader = new self();
		if ($leader->load($id)) {
			return $leader;
		} else {
			return false;
		}
	}

	public function __construct() {}

	public function getId() { return $this->id; }

	public function getName() { return $this->name; }
	public function setName($name) { $this->name = $name; }

	public function getPoint() { return $this->point; }
	public function setPoint($point) { $this->point = $point; }

	public function getCreation() { return $this->creation; }
	public function setCreation($creation) { $this->creation = $creation; }

	public function getToken() { return $this->token; }
	public function setToken($token)
	{
		if (!empty($token)) {
			$this->token = md5($token);
		}
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
			$ret[] = Question::get($value->search, $value->number);
		}
		return $ret;
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

		$result = $result->get(0);

		foreach (Question::getAllFromSearch($result->id) as $question) {
			if ($question->delete() === false) {
				return false;
			}
		}

		$db->search->removeFirst($result);
		$db->search->update();

		return true;
	}
}