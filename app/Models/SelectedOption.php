<?php

namespace App\Models;

class SelectedOption
{
	private $id;
	private $answer;
	private $option;
	private $text;

	public static function get($id)
	{
		$selected = new self();
		if ($selected->load($id)) {
			return $selected;
		} else {
			return false;
		}
	}

	public function __construct() {}

	public function getId() { return $this->id; }

	public function getAnswer()
	{
		return Answer::get($this->answer);
	}

	public function setAnswer($user)
	{
		if ($answer instanceof Answer) {
			$this->answer = $answer->getId();
		} else {
			$this->answer = $answer;
		}
	}

	public function getOption()
	{
		return Option::get($this->option);
	}

	public function setOption($option)
	{
		if ($option instanceof Option) {
			$this->option = $option->getId();
		} else {
			$this->option = $option;
		}
	}

	public function getText() { return $this->text; }
	public function setText($text) { $this->text = $text; }

	public static function getAll()
	{
		$db = new DataBase('selected_option');
		$ret = array();
		foreach ($db->selected_option as $value) {
			$ret[] = self::get($value->id);
		}
		return $ret;
	}

	public function insert()
	{
		$db = new DataBase('selected_option');

		$db->selected_option->append([
			'answer' => $this->answer,
			'option' => $this->option,
			'text' => $this->text
		]);
		$db->selected_option->update();
	}

	public function load($id)
	{
		$db = new DataBase('selected_option');

		$result = $db->selected_option->where( function($row) use($id) {
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
		$db = new DataBase('selected_option');

		$result = $db->selected_option->where( function($row) {
			return $row->id == $this->id;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);

		foreach ($result as $key => $value) {
			$result->{$key} = $this->{$key};
		}
		
		$db->selected_option->setValue($result);
		$db->selected_option->update();

		return true;
	}

	public function delete()
	{
		$db = new DataBase('selected_option');

		$result = $db->selected_option->where( function($row) {
			return $row->id == $this->id;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);

		$db->selected_option->removeFirst($result);
		$db->selected_option->update();

		return true;
	}
}