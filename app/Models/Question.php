<?php

namespace App\Models;

class Question
{
	private $id;
	private $search;
	private $number;
	private $title;
	private $text;
	private $creation;
	private $type;

	public static function get($id)
	{
		$question = new self();
		if ($question->load($id)) {
			return $question;
		} else {
			return false;
		}
	}

	public function __construct() {}

	public function getId() { return $this->id; }

	public function getSearch()
	{
		return Search::get($this->search);
	}

	public function setSearch($search)
	{
		if ($search instanceof Search) {
			$this->search = $search->getId();
		} else {
			$this->search = $search;
		}
	}

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
			$ret[] = self::get($value->id);
		}
		return $ret;
	}

	public function getOptions()
	{
		$db = new DataBase('option');
		$ret = array();
		
		foreach ($db->option->where( function($row) {
			return $row->question == $this->id;
		})->order( function($a, $b) {
			return intval($a->number) - intval($b->number);
		}) as $value) {
			$ret[] = Option::get($value->id);
		}

		return $ret;
	}

	public function getOption($number)
	{
		$db = new DataBase('option');

		$result = $db->option->where( function($row) use($number) {
			return $row->question == $this->id && $row->number == $number;
		});

		if ($result->size() == 0) {
			return false;
		}

		return Option::get($result->get(0)->id);
	}

	public function addOption(Option $option)
	{
		if ($option) {
			$option->setQuestion($this);
			return $option->insert();
		}
		return false;
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

	public function load($id)
	{
		$db = new DataBase('question');

		$result = $db->question->where( function($row) use($id) {
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
		$db = new DataBase('question');

		$result = $db->question->where( function($row) {
			return $row->id == $this->id;
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
			return $row->id == $this->id;
		});

		if ($result->size() == 0) {
			return false;
		}

		foreach ($this->getOptions() as $option) {
			if ($option->delete() === false) {
				return false;
			}
		}

		$result = $result->get(0);

		$db->question->removeFirst($result);
		$db->question->update();

		$search = $this->getSearch();
		$next = $this->number + 1;

		while (true) {
			$option = $search->getQuestion($next);

			if ($option === false) {
				break;
			}

			$option->setNumber($next - 1);
			$option->update();

			$next++;
		}

		return true;
	}
}