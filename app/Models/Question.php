<?php

namespace App\Models;

class Question
{
	private $search;
	private $number;
	private $title;
	private $text;
	private $creation;
	private $type;

	public static function get($search, $number)
	{
		$question = new self();
		if ($question->load($search, $number)) {
			return $question;
		} else {
			return false;
		}
	}

	public function __construct() {}

	public function getSearch() { return $this->search; }
	public function setSearch($search) { $this->search = $search; }

	public function getNumber() { return $this->number; }
	public function setNumber($number) { $this->number = $number; }

	public function getTitle() { return $this->title; }
	public function setTitle($title) { $this->title = $title; }

	public function getText() { return $this->text; }
	public function setText($text) { $this->text = $text; }

	public function getCreation() { return $this->creation; }
	public function setCreation($creation) { $this->creation = $creation; }

	public function getType() { return $this->type; }
	public function setType($type) { $this->type = $type; }

	public static function getAll()
	{
		$db = new DataBase('question');
		$ret = array();
		foreach ($db->question as $value) {
			$ret[] = self::get($value->search, $value->number);
		}
		return $ret;
	}

	public function getOptions()
	{
		$db = new DataBase('option');
		$ret = array();
		
		foreach ($db->option->where( function($row) {
			return $row->search == $this->search && $row->question_number == $this->number;
		})->order( function($a, $b) {
			return intval($a->number) - intval($b->number);
		}) as $value) {
			$ret[] = Option::get($value->search, $value->question_number, $value->number);
		}

		return $ret;
	}

	public function insert()
	{
		$db = new DataBase('question');

		$db->question->append([
			'search' => $this->search,
			'number' => $this->number,
			'title' => $this->title,
			'text' => $this->text,
			'type' => $this->type
		]);
		$db->question->update();
	}

	public function load($search, $number)
	{
		$db = new DataBase('question');

		$result = $db->question->where( function($row) use($search, $number) {
			return $row->search == $search && $row->number == $number;
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
		$db = new DataBase('question');

		$result = $db->question->where( function($row) {
			return $row->search == $this->search && $row->number == $this->number;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);

		foreach ($result as $key => $value) {
			$result->{$key} = $this->{$key};
		}
		
		$db->question->setValue($result);
		$db->question->update();

		return true;
	}

	public function delete()
	{
		$db = new DataBase('question');

		$result = $db->question->where( function($row) {
			return $row->search == $this->search && $row->number == $this->number;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);

		foreach (Option::getAllFromQuestion($result->search, $result->number) as $option) {
			if ($option->delete() === false) {
				return false;
			}
		}
		
		$db->question->removeFirst($result);
		$db->question->update();

		return true;
	}
}