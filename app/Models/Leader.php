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

	public function getMember()
	{
		return Member::get($this->id);
	}

	public function insert()
	{
		$db = new DataBase('leader');

		$db->leader->append([
			'id' => $this->id
		]);
		$db->leader->commit();
		$this->load($db->leader->getRowsInsert()->last()->id);
	}

	public function load($id)
	{
		$db = new DataBase('leader');

		$result = $db->leader->where( function($row) use($id) {
			return $row->id == $id;
		});

		if ($result->count() == 0) {
			return false;
		}

		$result = $result->first();
		$this->id = $result->id;

		return true;
	}

	public function update()
	{
		$db = new DataBase('leader');

		$result = $db->leader->where( function($row) {
			return $row->id == $this->id;
		});

		if ($result->count() == 0) {
			return false;
		}

		$result = $result->first();
		$result->id = $this->id;
		
		$db->leader->setValue($result);
		$db->leader->commit();

		return true;
	}

	public function delete()
	{
		$db = new DataBase('leader');

		$db->leader->removeWhere( function($row) {
			return $row->id == $this->id;
		});

		$db->leader->commit();

		return true;
	}
}