<?php

namespace App\Models;

class Leader
{
	private $id;

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
	public function setId($id) { $this->id = $id; }

	public static function getAll()
	{
		$db = new DataBase('leader');
		$ret = array();
		foreach ($db->leader as $value) {
			$ret[] = self::get($value->id);
		}
		return $ret;
	}

	public function insert()
	{
		$db = new DataBase('leader');

		$db->leader->append([
			'id' => $this->id
		]);
		$db->leader->update();
	}

	public function load($id)
	{
		$db = new DataBase('leader');

		$result = $db->leader->where( function($row) use($id) {
			return $row->id == $id;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);
		$this->id = $result->id;

		return true;
	}

	public function update()
	{
		$db = new DataBase('leader');

		$result = $db->leader->where( function($row) {
			return $row->id == $this->id;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);
		$result->id = $this->id;
		
		$db->leader->setValue($result);
		$db->leader->update();

		return true;
	}

	public function delete()
	{
		$db = new DataBase('leader');

		$result = $db->leader->where( function($row) {
			return $row->id == $this->id;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);

		$db->leader->removeFirst($result);
		$db->leader->update();

		return true;
	}
}