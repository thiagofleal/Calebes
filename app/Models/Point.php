<?php

namespace App\Models;

class Point
{
	private $id;
	private $name;
	private $address;

	public function __construct() {}

	public function getId() { return $this->id; }

	public function getName() { return $this->name; }
	public function setName($name) { $this->name = $name; }

	public function getAddress() { return $this->address; }
	public function setAddress($address) { $this->address = $address; }

	public static function getAll()
	{
		$db = new DataBase('point');
		return $db->point->get();
	}

	public function insert()
	{
		$db = new DataBase('point');

		$db->point->append([
			'name' => $this->name,
			'address' => $this->address
		]);
		$db->point->update();
	}

	public function load($id)
	{
		$db = new DataBase('point');

		$result = $db->point->where( function($row) use($id) {
			return $row->id == $id;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);
		$this->id = $result->id;
		$this->name = $result->name;
		$this->address = $result->address;

		return true;
	}

	public function update()
	{
		$db = new DataBase('point');

		$result = $db->point->where( function($row) use($id) {
			return $row->id == $id;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);
		$result->id = $this->id;
		$result->name = $this->name;
		$result->address = $this->address;

		$db->point->setValue($result);
		$db->point->update();

		return true;
	}

	public function delete()
	{
		$db = new DataBase('point');

		$result = $db->point->where( function($row) use($id) {
			return $row->id == $id;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);

		$db->point->removeValue($result);
		$db->point->update();

		return true;
	}
}