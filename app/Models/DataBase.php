<?php

namespace App\Models;

use Tonight\Data\MySQL;
use Tonight\MVC\Router;
use \PDO;
use \PDOException;
use \Exception;

class DataBase extends MySQL
{
	public function __construct($fields)
	{
		try
		{
			$con = array(
				'dbname' => 'caleb_mission',
				'host' => 'localhost'
			);
			$user = 'root';
			$pass = '';
			parent::__construct($con, $user, $pass);
			$this->start($fields);
			$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
			Router::redirect('erro', 'Conexão com banco de dados', $e->getMessage());
		}
		catch(Exception $e)
		{
			Router::redirect('erro', 'Erro interno', $e->getMessage());
		}
	}
}