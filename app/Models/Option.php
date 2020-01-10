<?php

namespace App\Models;

class Option
{
	private $search;
	private $question_number;
	private $number;
	private $text;
	private $insert;

	public static function get($search, $question_number, $number)
	{
		$option = new self();
		if ($option->load($search, $question_number, $number)) {
			return $option;
		} else {
			return false;
		}
	}

	public function __construct() {}

	public function getSearch() { return $this->search; }
	public function setSearch($search) { $this->search = $search; }

	public function getQuestionNumber() { return $this->question_number; }
	public function setQuestionNumber($question_number) { $this->question_number = $question_number; }

	public function getNumber() { return $this->number; }
	public function setNumber($number) { $this->number = $number; }
	
	public function getText() { return $this->text; }
	public function setText($text) { $this->text = $text; }
	
	public function getInsert() { return $this->insert; }
	public function setInsert($insert) { $this->insert = $insert; }
	
	public static function getAll()
	{
		$db = new DataBase('option');
		$ret = array();
		
		foreach ($db->option as $value) {
			$ret[] = self::get($value->search, $value->question_number, $value->number);
		}

		return $ret;
	}

	public function insert()
	{
		$db = new DataBase('option');

		$result = $db->option->where( function($row) {
			return
				$row->search == $this->search &&
				$row->question_number == $this->question_number &&
				$row->number == $this->number;
		});

		if ($result->size() > 0) {
			return false;
		}

		$db->option->append([
			'search' => $this->search,
			'question_number' => $this->question_number,
			'number' => $this->number,
			'text' => $this->text,
			'insert' => $this->insert
		]);
		$db->option->update();
		return true;
	}

	public function load($search, $question_number, $number)
	{
		$db = new DataBase('option');

		$result = $db->option->where( function($row) use($search, $question_number, $number) {
			return
				$row->search == $search &&
				$row->question_number == $question_number &&
				$row->number == $number;
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
		$db = new DataBase('option');

		$result = $db->option->where( function($row) {
			return
				$row->search == $this->search &&
				$row->question_number == $this->question_number &&
				$row->number == $this->number;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);

		foreach ($result as $key => $value) {
			$result->{$key} = $this->{$key};
		}

		$db->option->setValue($result);
		$db->option->update();

		return true;
	}

	public function delete()
	{
		$db = new DataBase('option');

		$result = $db->option->where( function($row) {
			return
				$row->search == $this->search &&
				$row->question_number == $this->question_number &&
				$row->number == $this->number;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);
		
		$db->option->removeFirst($result);
		$db->option->update();

		$next = $this->number + 1;
		$option = self::get($this->search, $this->question_number, $next);

		if ($option !== false) {
			$option->delete();
			$option->setNumber($next - 1);
			$option->insert();
		}

		return true;
	}
}