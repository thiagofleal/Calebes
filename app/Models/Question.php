<?php

namespace App\Models;

class Question
{
	private $search;
	private $number;
	private $text;
	private $creation;

	public static function get($search, $number)
	{
		$leader = new self();
		if ($leader->load($search, $number)) {
			return $leader;
		} else {
			return false;
		}
	}

	public function __construct() {}

	public function getSearch() { return $this->search; }
	public function setSearch($search) { $this->search = $search; }

	public function getNumber() { return $this->number; }
	public function setNumber($number) { $this->number = $number; }

	public function getText() { return $this->text; }
	public function setText($text) { $this->text = $text; }

	public function getCreation() { return $this->creation; }
	public function setCreation($creation) { $this->creation = $creation; }

	public static function getAll()
	{
		$db = new DataBase('question');
		$ret = array();
		foreach ($db->question as $value) {
			$ret[] = self::get($value->search, $value->number);
		}
		return $ret;
	}

	public static function getAllFromSearch($search_id)
	{
		$db = new DataBase('question');
		$ret = array();
		foreach ($db->question->where( function($row) use($search_id) {
			return $row->search == $search_id;
		}) as $value) {
			$ret[] = self::get($value->search, $value->number);
		}
		return $ret;
	}

	public function insert()
	{
		$db = new DataBase('question');

		$db->question->append([
			'search' => $this->search,
			'number' => $this->number,
			'text' => $this->text,
			'creation' => $this->creation
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

		$result = $result->get(0);

		$db->question->removeFirst($result);
		$db->question->update();

		return true;
	}
}