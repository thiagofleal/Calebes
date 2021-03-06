<?php

namespace App\Models;

class Point
{
	private $id;
	private $name;
	private $address;

	public static function get($id)
	{
		$point = new self();
		if ($point->load($id)) {
			return $point;
		} else {
			return false;
		}
	}

	public function __construct() {}

	public function getId() { return $this->id; }

	public function getName() { return $this->name; }
	public function setName($name) { $this->name = $name; }

	public function getAddress() { return $this->address; }
	public function setAddress($address) { $this->address = $address; }

	public static function getAll()
	{
		$db = new DataBase('point');
		$ret = array();
		foreach ($db->point as $value) {
			$ret[] = self::get($value->id);
		}
		return $ret;
	}

	public function getMembers()
	{
		$db = new DataBase('member');
		$ret = array();
		
		foreach ($db->member->where( function($row) {
			return $row->point == $this->id;
		}) as $value) {
			$ret[] = Member::get($value->id);
		}

		return $ret;
	}

	public function addMember(Member $member)
	{
		if ($member) {
			$member->setPoint($this);
			return $member->insert();
		}
		
		return false;
	}

	public function getResearches()
	{
		$db = new DataBase('search');
		$ret = array();
		foreach ($db->search->where( function($row) {
			return $row->point == $this->id;
		})->order( function($a, $b) {
			return strtotime($b->creation) - strtotime($a->creation);
		}) as $value) {
			$ret[] = Search::get($value->id);
		}
		return $ret;
	}

	public function getVisibleResearches()
	{
		$ret = array();
		foreach ($this->getResearches() as $search) {
			if ($search->isVisible()) {
				$ret[] = $search;
			}
		}
		return $ret;
	}

	public function addSearch(Search $search)
	{
		if ($search) {
			$search->setPoint($this);
			$search->insert();
			return true;
		}
		return false;
	}

	public function getLeaders()
	{
		$db = new DataBase('leader', 'member');
		$ret = array();
		foreach ($db->leader->join($db->member, function($leader, $member) {
			return $leader->id == $member->id;
		})->where( function($row) {
			return $row->point == $this->id;
		})->order( function($a, $b) {
			return strcmp($a->name, $b->name);
		}) as $value) {
			$ret[] = Member::get($value->id);
		}
		return $ret;
	}

	public function insert()
	{
		$db = new DataBase('point');

		$db->point->append([
			'name' => $this->name,
			'address' => $this->address
		]);
		$db->point->commit();
		$this->load($db->point->getRowsInsert()->last()->id);
	}

	public function load($id)
	{
		$db = new DataBase('point');

		$result = $db->point->where( function($row) use($id) {
			return $row->id == $id;
		});

		if ($result->count() == 0) {
			return false;
		}

		$result = $result->first();
		$this->id = $result->id;
		$this->name = $result->name;
		$this->address = $result->address;

		return true;
	}

	public function update()
	{
		$db = new DataBase('point');

		$result = $db->point->where( function($row) {
			return $row->id == $this->id;
		});

		if ($result->count() == 0) {
			return false;
		}

		$result = $result->first();
		$result->id = $this->id;
		$result->name = $this->name;
		$result->address = $this->address;

		$db->point->setValue($result);
		$db->point->commit();

		return true;
	}

	public function delete()
	{
		$db = new DataBase('point');

		$db->point->removeWhere( function($row) {
			return $row->id == $this->id;
		});

		$db->point->commit();

		return true;
	}
}