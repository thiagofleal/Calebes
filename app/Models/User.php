<?php

namespace App\Models;

class User
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
	public function setPassword($password) { $this->password = md5($password); }
	
	public function getPoint() { return $this->point; }
	public function setPoint($point) { $this->point = $point; }
	
	public function getRegister() { return $this->register; }
	public function setRegister($register) { $this->register = $register; }

	public static function getAll()
	{
		$db = new DataBase('user');
		return $db->user->get();
	}

	public function insert()
	{
		$db = new DataBase('user');

		$db->user->append([
			'document' => $this->document,
			'document_type' => $this->document_type,
			'name' => $this->name,
			'birth' => $this->birth,
			'address' => $this->address,
			'phone' => $this->phone,
			'email' => $this->email,
			'password' => $this->password,
			'point' => $this->point
		]);
		$db->user->update();
	}

	public function load($id)
	{
		$db = new DataBase('user');

		$result = $db->user->where( function($row) use($id) {
			return $row->id == $id;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);
		$this->document = $result->document;
		$this->document_type = $result->document_type;
		$this->name = $result->name;
		$this->birth = $result->birth;
		$this->address = $result->address;
		$this->phone = $result->phone;
		$this->email = $result->email;
		$this->password = $result->password;
		$this->point = $result->point;
		$this->register = $result->register;

		return true;
	}

	public function update()
	{
		$db = new DataBase('user');

		$result = $db->user->where( function($row) use($id) {
			return $row->id == $id;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);
		$result->document = $this->document;
		$result->document_type = $this->document_type;
		$result->name = $this->name;
		$result->birth = $this->birth;
		$result->address = $this->address;
		$result->phone = $this->phone;
		$result->email = $this->email;
		$result->password = $this->password;
		$result->point = $this->point;
		$result->register = $this->register;

		$db->user->setValue($result);
		$db->user->update();

		return true;
	}

	public function delete()
	{
		$db = new DataBase('user');

		$result = $db->user->where( function($row) use($id) {
			return $row->id == $id;
		});

		if ($result->size() == 0) {
			return false;
		}

		$result = $result->get(0);

		$db->user->removeValue($result);
		$db->user->update();

		return true;
	}
}