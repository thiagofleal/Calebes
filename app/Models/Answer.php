<?php

namespace App\Models;

class Answer
{
	private $id;
	private $user;
	private $search;
	private $time;

	public static function get($id)
	{
		$answer = new self();
		if ($answer->load($id)) {
			return $answer;
		} else {
			return false;
		}
	}

	public function __construct() {}

	public function getId() { return $this->id; }

	public function getUser()
	{
		return Member::get($this->user);
	}

	public function setUser($user)
	{
		if ($user instanceof Member) {
			$this->user = $user->getId();
		} else {
			$this->user = $user;
		}
	}

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

	public function getTime() { return $this->time; }
	public function setTime($time) { $this->time = $time; }

	public static function getAll()
	{
		$db = new DataBase('answer');
		$ret = array();
		foreach ($db->answer as $value) {
			$ret[] = self::get($value->id);
		}
		return $ret;
	}

	public function getOptions()
	{
		$db = new DataBase('question', 'option', 'selected_option');
		$ret = array();
		foreach ($db->selected_option->select( function($row) {
			$row->id_selected = $row->id;
			return $row;
		})->join($db->option, function($selected, $option) {
			return $selected->option == $option->id;
		})->join($db->question, function($selected, $question) {
			return $selected->question == $question->id;
		})->where( function($row) {
			return $row->answer == $this->id;
		}) as $value) {
			$ret[] = SelectedOption::get($value->id_selected);
		}
		return $ret;
	}

	public function addOption(SelectedOption $option)
	{
		if ($option) {
			$option->setAnswer($this);
			$option->insert();
			return true;
		}
		return false;
	}

	public function insert()
	{
		$db = new DataBase('answer');

		$db->answer->append([
			'user' => $this->user,
			'search' => $this->search
		]);
		$db->answer->commit();
		$this->load($db->answer->getRowsInsert()->last()->id);
	}

	public function load($id)
	{
		$db = new DataBase('answer');

		$result = $db->answer->where( function($row) use($id) {
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
		$db = new DataBase('answer');

		$result = $db->answer->where( function($row) {
			return $row->id == $this->id;
		});

		if ($result->count() == 0) {
			return false;
		}

		$result = $result->first();

		foreach ($result as $key => $value) {
			$result->{$key} = $this->{$key};
		}
		
		$db->answer->setValue($result);
		$db->answer->commit();

		return true;
	}

	public function delete()
	{
		$db = new DataBase('answer');

		foreach ($this->getOptions() as $option) {
			$option->delete();
		}

		$db->answer->removeWhere( function($row) {
			return $row->id == $this->id;
		});

		$db->answer->commit();

		return true;
	}
}