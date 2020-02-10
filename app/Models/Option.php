<?php

namespace App\Models;

class Option
{
	private $id;
	private $question;
	private $number;
	private $text;
	private $insert;

	public static function get($id)
	{
		$option = new self();
		if ($option->load($id)) {
			return $option;
		} else {
			return false;
		}
	}

	public function __construct() {}

	public function getId() { return $this->id; }
	
	public function getQuestion()
	{
		return Question::get($this->question);
	}

	public function setQuestion($question)
	{
		if ($question instanceof Question) {
			$this->question = $question->getId();
		} else {
			$this->question = $question;
		}
	}

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

	public function getSelected()
	{
		$db = new DataBase('selected_option');
		$ret = array();

		foreach ($db->selected_option->where( function($row) {
			return $row->option == $this->id;
		}) as $selected) {
			$ret[] = SelectedOption::get($selected->id);
		}

		return $ret;
	}

	public function insert()
	{
		$db = new DataBase('option');

		$result = $db->option->where( function($row) {
			return $row->id == $this->id;
		});

		if ($result->count() > 0) {
			return false;
		}

		$db->option->append([
			'question' => $this->question,
			'number' => $this->number,
			'text' => $this->text,
			'insert' => $this->insert
		]);
		$db->option->commit();
		$this->load($db->option->getRowsInsert()->last()->id);
		return true;
	}

	public function load($id)
	{
		$db = new DataBase('option');

		$result = $db->option->where( function($row) use($id) {
			return $row->id == $id;
		});

		if ($result->count() == 0) {
			return false;
		}

		$result = $result->first();

		foreach ($result as $key => $value) {
			$this->{$key} = $result->{$key};
		}

		return true;
	}

	public function update()
	{
		$db = new DataBase('option');

		$result = $db->option->where( function($row) {
			return $row->id == $this->id;
		});

		if ($result->count() == 0) {
			return false;
		}

		$result = $result->first();

		foreach ($result as $key => $value) {
			$result->{$key} = $this->{$key};
		}

		$db->option->setValue($result);
		$db->option->commit();

		return true;
	}

	public function delete()
	{
		$db = new DataBase('option');

		$db->option->removeWhere( function($row) {
			return $row->id == $this->id;
		});

		$db->option->commit();

		$question = $this->getQuestion();
		$next = $this->number + 1;

		while (true) {
			$option = $question->getOption($next);

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