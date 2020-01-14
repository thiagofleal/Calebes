<?php

namespace App\Models;

class Member
{
	private $id;
	private $document;
	private $document_type;
	private $name;
	private $birth;
	private $address;
	private $phone;
	private $email;
	private $password;
	private $point;
	private $register;
	private $tshirt_size;

	public static function get($id)
	{
		$member = new self();
		if ($member->load($id)) {
			return $member;
		} else {
			return false;
		}
	}

	public function __construct() {}

	public function getId() { return $this->id; }

	public function getDocument() { return $this->document; }
	public function setDocument($document) { $this->document = $document; }

	public function getDocument_type() { return $this->document_type; }
	public function setDocument_type($document_type) { $this->document_type = $document_type; }
	
	public function getName() { return $this->name; }
	public function setName($name) { $this->name = $name; }
	
	public function getBirth() { return $this->birth; }
	public function setBirth($birth) { $this->birth = $birth; }
	
	public function getAddress() { return $this->address; }
	public function setAddress($address) { $this->address = $address; }
	
	public function getPhone() { return $this->phone; }
	public function setPhone($phone) { $this->phone = $phone; }
	
	public function getEmail() { return $this->email; }
	public function setEmail($email) { $this->email = $email; }
	
	public function getPassword() { return $this->password; }
	public function setPassword($password)
	{
		if (!empty($password)) {
			$this->password = md5($password);
		}
	}
	
	public function getPoint()
	{
		return Point::get($this->point);
	}

	public function setPoint($point)
	{
		if ($point instanceof Point) {
			$this->point = $point->getId();
		} else {
			$this->point = $point;
		}
	}
	
	public function getRegister() { return $this->register; }
	public function setRegister($register) { $this->register = $register; }
	
	public function getTshirt_size() { return $this->tshirt_size; }
	public function setTshirt_size($tshirt_size) { $this->tshirt_size = $tshirt_size; }

	public static function getAll()
	{
		$db = new DataBase('member');
		$ret = array();
		
		foreach ($db->member as $value) {
			$ret[] = self::get($value->id);
		}

		return $ret;
	}

	public static function getNotPoint()
	{
		$db = new DataBase('member');
		$ret = array();
		
		foreach (
			$db->member->where( function($row) {
				return empty($row->point);
			}) as $value
		) {
			$ret[] = self::get($value->id);
		}

		return $ret;
	}

	public static function login($user, $password)
	{
		$db = new DataBase('member');
		
		$result = $db->member->where( function($row) use($user, $password) {
			return ($row->document == $user || $row->email == $user) && $row->password == md5($password);
		});

		if ($result->size() > 0) {
			return self::get($result->get(0)->id);
		} else {
			return false;
		}
	}

	public function insert()
	{
		$db = new DataBase('member');

		$result = $db->member->where( function($row) {
			return $row->document == $this->document || $row->email == $this->email;
		});

		if ($result->size() > 0) {
			return false;
		}

		$db->member->append([
			'document' => $this->document,
			'document_type' => $this->document_type,
			'name' => $this->name,
			'birth' => $this->birth,
			'address' => $this->address,
			'phone' => $this->phone,
			'email' => $this->email,
			'password' => $this->password,
			'point' => $this->point,
			'tshirt_size' => $this->tshirt_size
		]);
		$db->member->update();
		return true;
	}

	public function load($id)
	{
		$db = new DataBase('member');

		$result = $db->member->where( function($row) use($id) {
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
		$db = new DataBase('member');

		$result = $db->member->where( function($row) {
			return $row->document == $this->document || $row->email == $this->email;
		});

		if ($result->size() > 0) {
			return false;
		}

		$result = $db->member->where( function($row) {
			return $row->id == $this->id;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);

		foreach ($result as $key => $value) {
			$result->{$key} = $this->{$key};
		}

		$db->member->setValue($result);
		$db->member->update();

		return true;
	}

	public function delete()
	{
		$db = new DataBase('member');

		$result = $db->member->where( function($row) {
			return $row->id == $this->id;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);

		$db->member->removeFirst($result);
		$db->member->update();

		return true;
	}

	public function isLeader()
	{
		$leader = Leader::get($this->id);
		return $leader !== false;
	}

	public function addLeader()
	{
		if ($this->isLeader()) {
			return false;
		}

		$leader = new Leader();
		$leader->setId($this->id);
		return $leader->insert();
	}

	public function removeLeader()
	{
		$leader = Leader::get($this->id);

		if ($leader === false) {
			return false;
		}

		return $leader->delete();
	}
}